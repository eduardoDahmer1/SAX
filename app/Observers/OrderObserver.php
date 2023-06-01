<?php

namespace App\Observers;

use App\Jobs\ProcessOrderJob;
use App\Jobs\OrderBilling;
use App\Mail\RedplayLicenseMail;
use App\Models\License;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{

    /**
     * Handle the order "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        if (config('features.redplay_digital_product')) {
            if ($order->payment_status === 'Completed') {
                $cart = $order->cart;
                foreach ($cart['items'] as $cartItem) {
                    $product = $cartItem['item'];
                    if ($product->licenses) {
                        $licenseToBeSentByEmail = License::where('product_id', $product->id)->where('available', true)->first();
                        if ($licenseToBeSentByEmail) {
                            Log::debug('License to be Sent: ', [$licenseToBeSentByEmail]);

                            Mail::to($order->customer_email)->queue(new RedplayLicenseMail($order, $licenseToBeSentByEmail));

                            $licenseToBeSentByEmail->available = false;
                            $licenseToBeSentByEmail->update();
                        }
                    }
                }
            }
        }

        if (env('ENABLE_ORDER')) {
            if ($order->payment_status === 'Completed') {
                $parameters = [
                    'cod' => env('ORDER_COD'),
                    'pas' => env('ORDER_PASSWORD'),
                    'ope' => 15,
                    'ped' => $order->order_number,
                    'pdc' => $order->number_cec,
                ];

                $url = 'https://saxpy.dyndns.org:444/EcommerceApi/production.php?' . http_build_query($parameters);
                OrderBilling::dispatch($url, $order);
            }
        }
         
    }
    
    public function created(Order $order)
    {
        if (env('ENABLE_ORDER')) {
            $data = $order->cart;
            $skus = [];
            $price = [];

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    if (isset($item['item']['sku'])) {
                        $skus[] = $item['item']['sku'];
                        $price[] = $item['item']['price'];
                    }
                }
            }

            $parameters = [
                'cod' => env('ORDER_COD'),
                'pas' => env('ORDER_PASSWORD'),
                'ope' => 12,
                'ped' => $order->order_number,
                'sku' => implode(',', $skus),
                'gra' => '',
                'qtd' => $order->totalQty,
                'pre' => implode(',', $price),
                'cli' => $order->user_id,
                'obs' => ($order->shipping === 'pickup') ? $order->shipping . ', ' . $order->pickup_location : $order->shipping,
                'pgt' => 1,
                'nom' => $order->customer_name,
                'eml' => $order->customer_email,
                'nas' => 3,
                'sex' => 'M',
                'doc' => $order->customer_document,
                'fn1' => $order->customer_phone,
                'fn2' => '',
                'fn3' => '',
                'end' => $order->customer_address,
                'num' => $order->customer_address_number,
                'com' => $order->customer_complement,
                'bai' => $order->customer_district,
                'cid' => $order->customer_city,
                'uf' => $order->customer_state,
                'cep' => $order->customer_zip,
                'moe' => $order->currency_sign,
                'fre' => $order->shipping_cost,


            ];
            $url = 'https://saxpy.dyndns.org:444/EcommerceApi/production.php?' . http_build_query($parameters);
            ProcessOrderJob::dispatch($url, $order);
            
        }
    }
}
