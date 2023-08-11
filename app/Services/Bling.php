<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class Bling
{
    private string $url = "https://www.bling.com.br/Api/v3/oauth/";
    private string $access_token;
    private string $refresh_token;

    public function __construct(
        private string $client_id,
        private string $client_secret,
        private string $state,
    ) {
    }

    public function authorize(): RedirectResponse
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'state' => $this->state
        ];

        return redirect()->away($this->url . 'authorize?' . http_build_query($params));
    }

    public function generateTokens(string $code): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret)
        ])->post($this->url . 'token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
        ])->collect();

        $this->access_token = $response->access_token;
        $this->refresh_token = $response->refresh_token;
    }

    public function refreshAccessToken(): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret)
        ])->post($this->url . 'token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refresh_token,
        ])->collect();

        $this->access_token = $response->access_token;
    }
}
