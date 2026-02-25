<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use src\Domain\Config\Models\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('app', function ($view) {
            $key = Config::query()->where('type', 'color')->first()?->value ?? 'pink';
            $schemes = config('color-schemes', []);
            $palette = $schemes[$key] ?? $schemes['pink'] ?? [];
            $shades = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'];
            $accentPalette = collect($palette)->only($shades)->filter()->all();
            $view->with('accentPalette', $accentPalette);
        });
    }
}
