<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use src\Domain\Config\Models\Config;
use src\Domain\Config\Services\AccentPaletteGenerator;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $hex = Config::query()->where('type', 'color')->first()?->value;
        if (! $hex || ! AccentPaletteGenerator::isValidHex($hex)) {
            $hex = AccentPaletteGenerator::DEFAULT_HEX;
        }
        $palette = AccentPaletteGenerator::fromHex($hex);

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'csrf_token' => csrf_token(),
            'locale' => session('locale', config('app.locale')),
            'accentScheme' => [
                'palette' => $palette,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
