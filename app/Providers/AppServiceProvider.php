<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('*', function ($view) {

            // Usamos caché para no consultar la base de datos en cada recarga de página
            $settings = Cache::remember('app_settings', 3600, function () {
                try {
                    return Setting::pluck('value', 'key')->all();
                } catch (\Exception $e) {
                    return [];
                }
            });

            $view->with([
                'globalSettings' => $settings,
                'exchangeRate'   => $settings['bcv_rate'] ?? 0
            ]);
        });
    }
}
