<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MercadoLivre;
use App\Models\Product;
use App\Models\Order;
use App\Facades\MercadoLivre as FacadesMercadoLivre;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MeliController extends Controller
{
    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('front.index');
        }

        $meli = MercadoLivre::first();
        $meli->update(['authorization_code' => $request->code]);

        Artisan::call('generate:token');

        return redirect()->route('admin-gs-integrations-mercadolivre-index');
    }

    public function notifications(Request $request)
    {
        Log::notice("MELI Notification: ", [$request->all()]);

        $meli = MercadoLivre::first();
        $url = config('mercadolivre.api_base_url') . $request->resource;
        $headers = ["Authorization: Bearer " . $meli->access_token];
        $data = FacadesMercadoLivre::curlGet($url, $headers);

        if ($request->topic === "items") {
            $this->updateProductStockAndPrice($data);
        }

        if ($request->topic === "items_prices") {
            $this->updateProductPrice($data);
        }

        if ($request->topic === "orders_v2") {
            $this->createOrder($data);
        }

        return response()->json($request->all());
    }

    private function updateProductStockAndPrice($data)
    {
        if (isset($data['id'])) {
            Product::where('mercadolivre_id', $data['id'])->update([
                'mercadolivre_price' => $data['price'],
                'stock' => $data['available_quantity']
            ]);

            Log::notice("Produto ID: " . $data['id'] . " atualizado com sucesso via Mercado Livre.");
        }
    }

    private function updateProductPrice($data)
    {
        if (isset($data['id'])) {
            $newPrice = $data['prices'][0]->amount ?? null;
            if ($newPrice) {
                Product::where('mercadolivre_id', $data['id'])->update(['price' => $newPrice]);
                Log::notice("Preço do Produto ID: " . $data['id'] . " atualizado no e-Commerce.");
            }
        }
    }

    private function createOrder($data)
    {
        if (Order::where('order_number', $data['id'])->exists()) {
            return;
        }

        $order = new Order();
        $order->fill([
            'customer_email' => $data['buyer']->email,
            'customer_phone' => $data['buyer']->phone->area_code . $data['buyer']->phone->number,
            'customer_name' => $data['buyer']->first_name . " " . $data['buyer']->last_name,
            'customer_country' => 'Brazil',
            'currency_sign' => "R$",
            'currency_value' => 1,
            'method' => 'Pagamento Externo via Mercado Livre',
            'shipping' => 'pickup',
            'txnid' => $data['id'],
            'totalQty' => count($data['order_items']),
            'pay_amount' => $data['paid_amount'],
            'order_number' => $data['id'],
            'payment_status' => $data['status'] === 'paid' ? 'paid' : 'unpaid'
        ]);

        $order->cart = $this->buildCart($data['order_items'], $data['paid_amount']);
        $order->save();

        Log::notice("Pedido ID: " . $data['id'] . " sincronizado com sucesso no e-Commerce.");
    }

    private function buildCart($orderItems, $totalPrice)
    {
        $items = [];

        foreach ($orderItems as $key => $item) {
            $product = $this->convertMeliProductToStore($item->item);

            if ($product) {
                $items[$key] = [
                    'item' => $product,
                    'qty' => $item->quantity,
                    'stock' => $product->stock,
                    'price' => $product->price,
                    'size_qty' => "",
                    'color_qty' => "",
                    'color_price' => "",
                    'material_qty' => "",
                    'material' => "",
                    'material_price' => "",
                    'max_quantity' => "",
                    'size_price' => "",
                    'size' => "",
                    'color' => "",
                    'customizable_gallery' => null,
                    'customizable_name' => null,
                    'customizable_number' => null,
                    'customizable_logo' => null,
                    'agree_terms' => null,
                    'license' => '',
                    'dp' => '0',
                    'keys' => '',
                    'values' => ''
                ];
            }
        }

        return [
            'items' => $items,
            'totalQty' => count($orderItems),
            'totalPrice' => (double) $totalPrice
        ];
    }

    private function convertMeliProductToStore($item)
    {
        return Product::where('mercadolivre_id', $item->id)->first();
    }
}
