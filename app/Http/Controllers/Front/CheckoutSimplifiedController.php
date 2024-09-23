<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Currency;
use App\Models\OrderTrack;
use App\Models\Pagesetting;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\PaymentGateway;

use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CheckoutSimplifiedController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!app()->runningInConsole()) {
            if (!$this->storeSettings->is_cart) {
                return app()->abort(404);
            }
        }
    }

    public function loadpayment($slug1, $slug2)
    {
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $payment = $slug1;
        $pay_id = $slug2;
        $gateway = '';
        if ($pay_id != 0) {
            $gateway = PaymentGateway::findOrFail($pay_id);
        }
        return view('load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
    }

    public function create(Request $request)
    {
        // Verifica se o checkout simplificado está habilitado e se o request não está vazio
        if (!$this->storeSettings->is_simplified_checkout || empty($request->all())) {
            return view('errors.404');
        }
    
        // Verifica se há produtos no carrinho
        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }
    
        // Captura a moeda
        $curr = Currency::find(Session::get('currency', $this->storeSettings->currency_id));
        $first_curr = Currency::where('id', '=', 1)->first();
        $order = new Order;
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $totalQty = $cart->totalQty;
        $item_number = Str::random(4) . time();
        $coupon_disc = Session::get('coupon') / $curr->value;
        $success_url = action('Front\PaymentController@payreturnSimplifidCheckout');
    
        // Verifica as quantidades dos produtos
        foreach ($cart->items as $key => $prod) {
            if (!empty($prod['max_quantity']) && ($prod['qty'] > $prod['max_quantity'])) {
                return redirect()->route('front.cart')->with('unsuccess', __('Max quantity of :prod is :qty!', ['prod' => $prod['item']['name'], 'qty' => $prod['max_quantity']]));
            }
    
            if (!empty($prod['item']['stock']) && ($prod['qty'] > $prod['item']['stock'])) {
                return redirect()->route('front.cart')->with('unsuccess', __('The stock of :prod is :qty!', ['prod' => $prod['item']['name'], 'qty' => $prod['item']['stock']]));
            }
        }
    
        // Calcula o total do carrinho
        $cart_total = Session::has('coupon_total') ? Session::get('coupon_total') / $curr->value : (Session::has('coupon_total1') ? Session::get('coupon_total1') / $curr->value : $oldCart->totalPrice * (1 + ($this->storeSettings->tax / 100)));
        // Adiciona custos de embalagem e frete
        $cart_total += $order['packing_cost'] + $order['shipping_cost'];
        $cart_total_currency = $cart_total * $curr->value;
        // Prepara o pedido
        $order['cart'] = [
            'items' => $cart->items,
            'totalQty' => $cart->totalQty,
            'totalPrice' => $cart->totalPrice
        ];
        
        $order['totalQty'] = $totalQty;
        $order['pay_amount'] = round($cart_total, 2);
        $order['method'] = 'Simplified';
        $order['order_number'] = $item_number;
        $order['payment_status'] = "Pending";
        $order['user_id'] = $request->user_id;
        $order['tax'] = $this->storeSettings->tax;
        $order['coupon_code'] = Session::get('coupon');
        $order['coupon_discount'] = $coupon_disc;
        $order['currency_sign'] = $curr->sign;
        $order['currency_value'] = $curr->value;
        $order['customer_email'] = " ";
        $order['customer_name'] = $request->name ?? " ";
        $order['customer_phone'] = $request->phone ?? " ";
        $order['customer_country'] = " ";
        $order['shipping_cost'] = 0;
    
        // Adiciona os novos campos
        $order['location'] = $request->location;
        $order['delivery_method'] = $request->delivery_method;
        $order['description'] = $request->description;
        $order['payment_method'] = $request->payment_method;
        $order['payment'] = $request->payment;
        // Salva o pedido
        $order->save();
        // Rastreia o pedido
        $track = new OrderTrack;
        $track->title = __('Pending');
        $track->text = __('You have successfully placed your order.');
        $track->order_id = $order->id;
        $track->save();
        // Cria uma notificação
        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();
        // Atualiza o uso do cupom
        if ($request->coupon_id != "") {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->used++;
            if ($coupon->times != null) {
                $coupon->times = (string)((int)$coupon->times - 1);
            }
            $coupon->update();
        }

        // Atualiza o estoque dos produtos
        foreach ($cart->items as $prod) {
            $x = $prod['size_qty'];
            $y = $prod['color_qty'];
            $z = $prod['material_qty'];
            $product = Product::findOrFail($prod['item']['id']);
            
            if (is_string($x) && !empty($x)) {
                $product->size_qty = $this->updateSizeQty($product->size_qty, $prod['size_key'], $prod['qty']);
            } elseif (is_string($y) && !empty($y)) {
                $product->color_qty = $this->updateColorQty($product->color_qty, $prod['color_key'], $prod['qty']);
            } elseif (is_string($z) && !empty($z)) {
                $product->material_qty = $this->updateMaterialQty($product->material_qty, $prod['material_key'], $prod['qty']);
            } else {
                $product->stock -= $prod['qty'];
            }
            
            if ($product->stock <= 5) {
                $notification = new Notification;
                $notification->product_id = $product->id;
                $notification->save();
            }
            $product->update();
        }
    
        // Cria pedidos de fornecedores
        $this->createVendorOrders($cart, $order);
        // Limpa o carrinho e as sessões temporárias
        Session::forget(['cart', 'already', 'coupon', 'coupon_code', 'coupon_total', 'coupon_total1', 'coupon_percentage']);
        Session::put('temporder', $order);
        Session::put('tempcart', $cart);
        // Envia email para o administrador
        $this->sendAdminEmail($order);
    
        return redirect($success_url);
    }
    
    // Funções auxiliares
    protected function updateSizeQty($sizeQty, $sizeKey, $qty)
    {
        if (is_string($sizeQty)) {
            $sizeQty = explode(',', $sizeQty);
            $sizeQty[$sizeKey] -= $qty;
            return implode(',', $sizeQty);
        }
        return $sizeQty; // Retorna sem alteração se não for uma string
    }
    
    protected function updateColorQty($colorQty, $colorKey, $qty)
    {
        if (is_string($colorQty)) {
            $colorQty = explode(',', $colorQty);
            $colorQty[$colorKey] -= $qty;
            return implode(',', $colorQty);
        }
        return $colorQty; // Retorna sem alteração se não for uma string
    }
    
    protected function updateMaterialQty($materialQty, $materialKey, $qty)
    {
        if (is_string($materialQty)) {
            $materialQty = explode(',', $materialQty);
            $materialQty[$materialKey] -= $qty;
            return implode(',', $materialQty);
        }
        return $materialQty; // Retorna sem alteração se não for uma string
    }
    
    protected function createVendorOrders($cart, $order)
    {
        $notf = [];
    
        foreach ($cart->items as $prod) {
            if ($prod['item']['user_id'] != 0) {
                $vorder = new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = $prod['item']['user_id'];
                $notf[] = $prod['item']['user_id'];
                $vorder->qty = $prod['qty'];
                $vorder->price = $prod['price'];
                $vorder->order_number = $order->order_number;
                $vorder->save();
            }
        }
    
        // Notificações para usuários
        if (!empty($notf)) {
            $users = array_unique($notf);
            foreach ($users as $user) {
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();
            }
        }
    }
    
    protected function sendAdminEmail($order)
    {
        $data = [
            'to' => Pagesetting::find(1)->contact_email,
            'subject' => __("New Order Received!"),
            'body' => $this->storeSettings->title . "<br>" . __("Hello Admin!") . "<br>" .
                __("Your store has received a new order.") . "<br>" .
                __("Order Number is") . " " . $order->order_number . "." . __("Please login to your panel to check.") . "<br>" .
                __("Thank you"),
        ];
    
        $mailer = new GeniusMailer();
        $mailer->sendAdminMail($data, $order->id);
    }
    
}
