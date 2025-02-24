<?php

namespace App\Console\Commands;

use App\Models\Generalsetting;
use App\Services\Bling;
use Illuminate\Console\Command;
class RefreshAccessTokenBling extends Command
{
    protected $signature = 'bling:refresh';
    protected $description = 'Refresh access token of Bling';
    public function handle()
    {
        $gs = Generalsetting::first();
        $bling = new Bling($gs->bling_access_token, $gs->bling_refresh_token);
        $bling->refreshAccessToken();
        $gs->bling_access_token = $bling->access_token ?? $gs->bling_access_token;
        $gs->save();
        return 0;
    }
}
