<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
class SetLocale
{

    public function handle($request, Closure $next)
    {
        $this->storeSettings = resolve('storeSettings');
        $this->storeLocale = resolve('storeLocale');
        $this->adminLocale = resolve('adminLocale');
        $this->newLangId = session()->get('language');
        $storeLocale = $this->storeLocale;
        if(config("features.lang_switcher") && $this->storeSettings->is_language && $this->newLangId) {
            $storeLocale = Language::find($this->newLangId);
        }
        App::setLocale(Route::is('admin*') ? "admin_{$this->adminLocale->name}" : $storeLocale->locale);
    
        return $next($request);
    }
}
