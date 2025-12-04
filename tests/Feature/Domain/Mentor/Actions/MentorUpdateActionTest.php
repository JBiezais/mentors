<?php

namespace Tests\Feature\Domain\Mentor\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Actions\MentorUpdateAction;
use src\Domain\Mentor\DTO\MentorUpdateData;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class MentorUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_mentor_email(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'email' => 'old@example.com',
        ]);

        $data = MentorUpdateData::from([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => $mentor->phone,
            'email' => 'new@example.com',
            'year' => $mentor->year,
            'about' => $mentor->about,
            'why' => $mentor->why,
            'lv' => $mentor->lv,
            'ru' => $mentor->ru,
            'en' => $mentor->en,
        ]);

        MentorUpdateAction::execute($mentor, $data);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'email' => 'new@example.com',
        ]);
    }

    public function test_it_updates_mentor_phone(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37120000000',
        ]);

        $data = MentorUpdateData::from([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37129999999',
            'email' => $mentor->email,
            'year' => $mentor->year,
            'about' => $mentor->about,
            'why' => $mentor->why,
            'lv' => $mentor->lv,
            'ru' => $mentor->ru,
            'en' => $mentor->en,
        ]);

        MentorUpdateAction::execute($mentor, $data);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'phone' => '+37129999999',
        ]);
    }

    public function test_it_updates_all_mentor_fields(): void
    {
        $faculty1 = Faculty::factory()->create(['code' => 'F1']);
        $faculty2 = Faculty::factory()->create(['code' => 'F2']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty1->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty2->id]);

        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty1->id,
            'program_id' => $program1->id,
            'phone' => 'old_phone',
            'email' => 'old@example.com',
            'year' => 1,
            'about' => 'Old about',
            'why' => 'Old why',
            'lv' => false,
            'ru' => false,
            'en' => false,
        ]);

        $data = MentorUpdateData::from([
            'id' => $mentor->id,
            'faculty_id' => $faculty2->id,
            'program_id' => $program2->id,
            'phone' => 'new_phone',
            'email' => 'new@example.com',
            'year' => 3,
            'about' => 'New about',
            'why' => 'New why',
            'lv' => true,
            'ru' => true,
            'en' => true,
        ]);

        MentorUpdateAction::execute($mentor, $data);

        $mentor->refresh();

        $this->assertEquals($faculty2->id, $mentor->faculty_id);
        $this->assertEquals($program2->id, $mentor->program_id);
        $this->assertEquals('new_phone', $mentor->phone);
        $this->assertEquals('new@example.com', $mentor->email);
        $this->assertEquals(3, $mentor->year);
        $this->assertEquals('New about', $mentor->about);
        $this->assertEquals('New why', $mentor->why);
        $this->assertTrue((bool)$mentor->lv);
        $this->assertTrue((bool)$mentor->ru);
        $this->assertTrue((bool)$mentor->en);
    }

    public function test_it_only_updates_specified_mentor(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $mentor1 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'email' => 'mentor1@example.com',
        ]);

        $mentor2 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'email' => 'mentor2@example.com',
        ]);

        $data = MentorUpdateData::from([
            'id' => $mentor1->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => $mentor1->phone,
            'email' => 'updated@example.com',
            'year' => $mentor1->year,
            'about' => $mentor1->about,
            'why' => $mentor1->why,
            'lv' => $mentor1->lv,
            'ru' => $mentor1->ru,
            'en' => $mentor1->en,
        ]);

        MentorUpdateAction::execute($mentor1, $data);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor1->id,
            'email' => 'updated@example.com',
        ]);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor2->id,
            'email' => 'mentor2@example.com',
        ]);
    }
}
