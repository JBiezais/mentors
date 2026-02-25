<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use src\Domain\Config\Models\Config;
use src\Domain\Config\Models\HeroGalleryImage;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;
use Tests\TestCase;

class ConfigControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_config_settings(): void
    {
        $user = User::factory()->create();
        Config::create(['type' => 'color', 'value' => 'pink']);
        HeroGalleryImage::create(['path' => 'image/banner.jpg', 'sort_order' => 0]);
        HeroGalleryImage::create(['path' => 'image/bg.jpg', 'sort_order' => 1]);

        $response = $this->actingAs($user)->get(route('config'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Config')
            ->where('color', 'pink')
            ->has('heroGallery', 2)
            ->where('heroGallery.0.id', 1)
            ->where('heroGallery.0.path', 'image/banner.jpg')
            ->where('heroGallery.1.path', 'image/bg.jpg')
        );
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('config'));

        $response->assertRedirect(route('login'));
    }

    public function test_archive_deletes_all_mail_records(): void
    {
        $user = User::factory()->create();
        Mail::factory()->count(3)->create();

        $this->actingAs($user)->post(route('archive'));

        $this->assertDatabaseCount('mails', 0);
    }

    public function test_archive_soft_deletes_all_mentor_records(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentors = Mentor::factory()->count(3)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $this->actingAs($user)->post(route('archive'));

        foreach ($mentors as $mentor) {
            $this->assertSoftDeleted('mentors', ['id' => $mentor->id]);
        }
    }

    public function test_archive_soft_deletes_all_student_records(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $students = Student::factory()->count(3)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $this->actingAs($user)->post(route('archive'));

        foreach ($students as $student) {
            $this->assertSoftDeleted('students', ['id' => $student->id]);
        }
    }

    public function test_archive_requires_authentication(): void
    {
        $response = $this->post(route('archive'));

        $response->assertRedirect(route('login'));
    }

    public function test_design_updates_color_config(): void
    {
        $user = User::factory()->create();
        Config::create(['type' => 'color', 'value' => 'pink']);

        $response = $this->actingAs($user)->post(route('design'), [
            'color' => 'red',
        ]);

        $response->assertRedirect(route('config'));
        $this->assertDatabaseHas('configs', [
            'type' => 'color',
            'value' => 'red',
        ]);
    }

    public function test_design_requires_authentication(): void
    {
        $response = $this->post(route('design'), [
            'color' => 'red',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_design_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('design'), []);

        $response->assertSessionHasErrors(['color']);
    }

    public function test_design_rejects_invalid_color_scheme(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('design'), [
            'color' => '#ff0000',
        ]);

        $response->assertSessionHasErrors(['color']);
    }

    public function test_store_hero_gallery_image_adds_images(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('hero.jpg', 800, 600);

        $response = $this->actingAs($user)->post(route('config.hero-gallery.store'), [
            'images' => [$file],
        ]);

        $response->assertRedirect(route('config'));
        $this->assertDatabaseCount('hero_gallery_images', 1);
        $this->assertDatabaseHas('hero_gallery_images', [
            'sort_order' => 0,
        ]);
    }

    public function test_destroy_hero_gallery_image_removes_image(): void
    {
        $user = User::factory()->create();
        $image = HeroGalleryImage::create(['path' => 'image/test.jpg', 'sort_order' => 0]);

        $response = $this->actingAs($user)->delete(route('config.hero-gallery.destroy', $image));

        $response->assertRedirect(route('config'));
        $this->assertDatabaseMissing('hero_gallery_images', ['id' => $image->id]);
    }

    public function test_reorder_hero_gallery_updates_sort_order(): void
    {
        $user = User::factory()->create();
        $img1 = HeroGalleryImage::create(['path' => 'image/a.jpg', 'sort_order' => 0]);
        $img2 = HeroGalleryImage::create(['path' => 'image/b.jpg', 'sort_order' => 1]);

        $response = $this->actingAs($user)->patch(route('config.hero-gallery.reorder'), [
            'ids' => [$img2->id, $img1->id],
        ]);

        $response->assertRedirect(route('config'));
        $this->assertDatabaseHas('hero_gallery_images', ['id' => $img2->id, 'sort_order' => 0]);
        $this->assertDatabaseHas('hero_gallery_images', ['id' => $img1->id, 'sort_order' => 1]);
    }

    public function test_get_statistics_returns_faculty_mentor_mentee_excel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('config.statistics', 'facultyMentorMentee'));

        $response->assertOk();
        $response->assertDownload('FacultyMentorMentee.xlsx');
    }

    public function test_get_statistics_requires_authentication(): void
    {
        $response = $this->get(route('config.statistics', 'facultyMentorMentee'));

        $response->assertRedirect(route('login'));
    }

    public function test_get_statistics_returns_null_for_invalid_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('config.statistics', 'invalidType'));

        $response->assertOk();
    }
}
