<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;
use App\Models\MercadoLivre;
use Illuminate\Support\Facades\Log;

class GenerateAccessToken extends Command
{
    protected $signature = 'generate:token';
    protected $description = 'Generates Access Token for Mercado Livre.';
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
            "grant_type" => 'authorization_code',
            "client_id" => $meli->app_id,
            "client_secret" => $meli->client_secret,
            "code" => $meli->authorization_code,
            "redirect_uri" => $meli->redirect_uri,
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "oauth/token",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/x-www-form-urlencoded",
            ),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($data),
        ));
        $resp = curl_exec($curl);
        $resp = (array) json_decode($resp);
        if(!array_key_exists('status', $resp)) {
            $meli = MercadoLivre::first();
            $meli->access_token = $resp['access_token'];
            $meli->refresh_token = $resp['refresh_token'];
            $meli->update();

            Log::info("MELI_GENERATE_ACCESS_TOKEN_SUCCESS: ", [$resp]);
            return true;
        }
        if(array_key_exists('status', $resp)) {
            Log::error('MELI_GENERATE_ACCESS_TOKEN_ERROR: ', [$resp]);
            return false;
        }
    }
}
