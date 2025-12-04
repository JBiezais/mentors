<?php

namespace Tests\Feature\Domain\Mentor\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Actions\MentorCreateAction;
use src\Domain\Mentor\DTO\MentorCreateData;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class MentorCreateActionTest extends TestCase
{
    use RefreshDatabase;

    private function createMentorData(Faculty $faculty, Program $program): MentorCreateData
    {
        return MentorCreateData::from([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'john.doe@example.com',
            'mentees' => 3,
            'year' => 2,
            'about' => 'About me text',
            'why' => 'Why I want to be a mentor',
            'lv' => true,
            'ru' => false,
            'en' => true,
            'privacy' => true,
            'key' => 'random_key_12345678901',
        ]);
    }

    public function test_it_creates_mentor_with_valid_data_without_image(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createMentorData($faculty, $program);

        MentorCreateAction::execute($data, null);

        $this->assertDatabaseHas('mentors', [
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_it_creates_mentor_with_image(): void
    {
        Storage::fake('local');

        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createMentorData($faculty, $program);
        $file = UploadedFile::fake()->image('avatar.jpg', 500, 500);

        // Create the cropped directory so Intervention Image can write to it
        // Intervention Image saves to a path relative to the project root
        $croppedPath = base_path('image/cropped');
        if (!is_dir($croppedPath)) {
            mkdir($croppedPath, 0755, true);
        }

        MentorCreateAction::execute($data, $file);

        $mentor = Mentor::where('email', 'john.doe@example.com')->first();

        $this->assertNotNull($mentor);
        $this->assertNotNull($mentor->img);

        // Cleanup
        if (is_dir($croppedPath)) {
            array_map('unlink', glob("$croppedPath/*"));
            @rmdir($croppedPath);
            @rmdir(base_path('image'));
        }
    }

    public function test_it_creates_mail_verification_record(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createMentorData($faculty, $program);

        MentorCreateAction::execute($data, null);

        $mentor = Mentor::where('email', 'john.doe@example.com')->first();

        $this->assertDatabaseHas('mails', [
            'type' => 'verification',
        ]);

        $mail = Mail::where('type', 'verification')->first();
        $this->assertContains($mentor->id, $mail->mentor_ids);
    }

    public function test_it_stores_all_mentor_fields(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createMentorData($faculty, $program);

        MentorCreateAction::execute($data, null);

        $mentor = Mentor::where('email', 'john.doe@example.com')->first();

        $this->assertEquals($faculty->id, $mentor->faculty_id);
        $this->assertEquals($program->id, $mentor->program_id);
        $this->assertEquals('John', $mentor->name);
        $this->assertEquals('Doe', $mentor->lastName);
        $this->assertEquals('+37120000000', $mentor->phone);
        $this->assertEquals(3, $mentor->mentees);
        $this->assertEquals(2, $mentor->year);
        $this->assertEquals('About me text', $mentor->about);
        $this->assertEquals('Why I want to be a mentor', $mentor->why);
        $this->assertTrue((bool)$mentor->lv);
        $this->assertFalse((bool)$mentor->ru);
        $this->assertTrue((bool)$mentor->en);
        $this->assertTrue((bool)$mentor->privacy);
        $this->assertEquals('random_key_12345678901', $mentor->key);
    }

    public function test_mentor_is_created_with_default_status(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createMentorData($faculty, $program);

        MentorCreateAction::execute($data, null);

        $mentor = Mentor::where('email', 'john.doe@example.com')->first();

        $this->assertEquals(0, $mentor->status);
    }
}
