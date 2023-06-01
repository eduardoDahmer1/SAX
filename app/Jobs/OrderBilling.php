<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class OrderBilling implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url, $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, Order $order)
    {
        $this->url = $url;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = Http::withoutVerifying()->post($this->url);
        } catch (HttpResponseException $httpError) {
            echo ('Erro na requisição: ' . $httpError->getMessage());
        }

        if ($response->successful()) {
            $request = $response->collect();
            foreach ($request as $data) {
                if ($data['estatus'] == 0) {
                    $this->order->billing = 1;
                    $this->order->update();
                }
            }
        }
    }
}
