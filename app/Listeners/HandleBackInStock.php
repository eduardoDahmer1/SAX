<?php

namespace App\Listeners;

use App\Models\BackInStock;
use App\Classes\GeniusMailer;

class HandleBackInStock
{
    public function handle(\App\Events\BackInStock $event)
    {
        $product = $event->product();
        $backInStocks = BackInStock::where('product_id', $product->id)->get();

        if ($backInStocks->isEmpty()) {
            return;
        }

        $productUrl = route('front.product', $product->slug);
        $storeSettings = $event->storeSettings();
        $emailList = $backInStocks->pluck('email')->all();

        if ($storeSettings->is_smtp) {
            $mailer = new GeniusMailer();
            foreach ($emailList as $email) {
                $mailData = [
                    'to' => $email,
                    'type' => "back_in_stock",
                    'product' => '<a target="_blank" href="' . $productUrl . '"> ' . $product->name . ' </a>',
                ];
                $mailer->sendAutoMail($mailData);
            }

            BackInStock::where('product_id', $product->id)->delete();
        }
    }
}
