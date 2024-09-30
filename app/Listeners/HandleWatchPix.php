<?php

namespace App\Listeners;

use App\Events\WatchPix;
use App\Models\Generalsetting;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
class HandleWatchPix implements ShouldQueue
{
    use InteractsWithQueue;
    private $order;
    private $storeSettings;
    public $timeout = 600;
    public $tries = 100;
    public function __construct()
    {
        $this->storeSettings = GeneralSetting::where('is_default', 1)->firstOrFail();
    }
    public function handle(WatchPix $event)
    {
        $this->order = $event->getOrder();
        $tries = 0;
        while ($tries < 5) {
            Log::debug("Trying to get PIX...");
            $pix = $this->getPix();
            $pix_status = $pix->status_request->status;
            if ($pix_status == "paid") {
                $this->order->payment_status = "Completed";
                $this->order->update();
                return;
            }
            if ($pix_status == "canceled") {
                $this->order->status = "declined";
                $this->order->payment_status = "Pending";
                $this->order->update();
                return;
            }
            $tries++;
            sleep(60);
        }
        Log::debug('5 tentativas de resgatar o PIX, cancelando pedido...');
        $this->order->status = "declined";
        $this->order->payment_status = "Pending";
        $this->order->update();
    }
    private function getPix()
    {
        $transaction_id = $this->order->txnid;
        $token = $this->storeSettings->paghiper_token;
        $api_key = $this->storeSettings->paghiper_api_key;
        $data = [
            "token" => $token,
            "apiKey" => $api_key,
            "transaction_id" => $transaction_id,
        ];
        $headers = [
            "Accept: application/json",
            "Accept-Charset: UTF-8",
            "Accept-Encoding: application/json",
            "Content-Type: application/json;charset=UTF-8",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pix.paghiper.com/invoice/status/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $json = json_decode($result);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 201) {
            Log::debug('paghiper_pix_status_response', [$json]);
        }
        curl_close($ch);
        return $json;
    }
}
