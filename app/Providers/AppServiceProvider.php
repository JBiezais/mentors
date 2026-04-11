<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use src\Domain\Config\Models\Config;
use src\Domain\Config\Services\AccentPaletteGenerator;

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
            $hex = Config::query()->where('type', 'color')->first()?->value;
            if (! $hex || ! AccentPaletteGenerator::isValidHex($hex)) {
                $hex = AccentPaletteGenerator::DEFAULT_HEX;
            }
            $accentPalette = AccentPaletteGenerator::fromHex($hex);
            $view->with('accentPalette', $accentPalette);
        });
    }
}
