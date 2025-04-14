<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Generalsetting;
use App\Observers\OrderObserver;
use App\View\Components\Header\Theme15;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::component('front.themes.theme-15.components.header', Theme15::class);
        
        if (!app()->runningInConsole()) {
            Paginator::useBootstrap();

            $currentUrl = str_replace(['http://', 'https://'], '', url()->current());

            $storeSettings = Generalsetting::whereRaw("'{$currentUrl}' LIKE CONCAT(domain,'%')")->first() ?? $this->getStoreSettings();

            if (!$storeSettings->id && Schema::hasTable('generalsettings') && Generalsetting::count() > 0) {
                $this->forgetGeneralSettingsCache();
            }

            Generalsetting::saving(fn() => $this->forgetGeneralSettingsCache());
            Generalsetting::updated(fn() => $this->forgetGeneralSettingsCache());

            Order::observe(OrderObserver::class);

            if (env('APP_ENV') === 'production') {
                URL::forceScheme('https');
            }

            $locales = Language::all();
            $storeLocale = $locales->find($storeSettings->lang_id);
            $currencies = Currency::all();
            $storeCurrency = $currencies->find($storeSettings->currency_id);
            $lang = $locales->find(1);

            $this->prepareLocaleFiles($locales);

            app()->instance('storeLocale', $storeLocale);
            app()->instance('locales', $locales);
            app()->instance('storeCurrency', $storeCurrency);
            app()->instance('currencies', $currencies);
            app()->instance('lang', $lang);
            app()->instance('storeSettings', $storeSettings);
        }

        if (app()->runningInConsole()) {
            app()->instance('storeSettings', new Generalsetting);
        }

        Blade::if('wedding', fn() => config('features.wedding_list'));
    }

    public function register()
    {
        if (env('REDIRECT_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }
        
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    protected function getStoreSettings()
    {
        return Cache::remember("storeSettings", 3600, function () {
            return Schema::hasTable('generalsettings') ? Generalsetting::where('is_default', 1)->first() : new Generalsetting;
        });
    }

    protected function forgetGeneralSettingsCache()
    {
        Cache::forget("storeSettings");
        return $this->getStoreSettings();
    }

    private function prepareLocaleFiles($locales)
    {
        $locales->each(function ($data) {
            $currentFile = lang_path($data->file);
            $baseFile = lang_path("base/{$data->locale}.json");

            if (!file_exists($baseFile)) {
                throw new \Exception("No base file found for {$data->locale}. Please make sure to add the file to /lang/base/{$data->locale}.json.");
            }

            if (!file_exists($currentFile)) {
                File::copy($baseFile, $currentFile);
            }
        });
    }
}
