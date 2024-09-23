<?php

namespace App\Providers;

use App\Models\MercadoLivre;
use Illuminate\Support\ServiceProvider;

class MercadoLivreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('mercadolivre', function() {
            return new MercadoLivre();
        });
    }
    public function boot()
    {
   
    }
}
