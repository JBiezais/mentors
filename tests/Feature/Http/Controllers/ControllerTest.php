<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use src\Domain\Config\Models\HeroGalleryImage;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\User\Models\User;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_displays_hero_gallery(): void
    {
        HeroGalleryImage::create(['path' => 'image/hero1.jpg', 'sort_order' => 0]);
        HeroGalleryImage::create(['path' => 'image/hero2.jpg', 'sort_order' => 1]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/home/Home')
            ->has('heroGallery')
            ->where('heroGallery', ['image/hero1.jpg', 'image/hero2.jpg'])
        );
    }

    public function test_home_returns_empty_hero_gallery_when_none_exists(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/home/Home')
            ->where('heroGallery', [])
        );
    }
}
