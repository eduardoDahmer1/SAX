<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;
use App\Models\MercadoLivre;
use Illuminate\Support\Facades\Log;
class RegenerateAccessToken extends Command
{
    protected $signature = 'regenerate:token';
    protected $description = 'Regenerates Access Token for Mercado Livre.';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $url = config('mercadolivre.api_base_url');
        $curl = curl_init();
        $meli = MercadoLivre::first();
        $data = [
            "grant_type" => 'refresh_token',
            "client_id" => $meli->app_id,
            "client_secret" => $meli->client_secret,
            "refresh_token" => $meli->refresh_token
        ];
        $query_string =  "oauth/token?grant_type={$data['grant_type']}&client_id={$data['client_id']}&client_secret={$data['client_secret']}&refresh_token={$data['refresh_token']}";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $query_string,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);
        if($resp) {
            $meli->access_token = $resp->access_token;
            $meli->refresh_token = $resp->refresh_token;

            $meli->update();
            Log::info("MELI_REGENERATE_ACCESS_TOKEN_SUCCESS: ", [$meli]);
            return true;
        }
    }
}
