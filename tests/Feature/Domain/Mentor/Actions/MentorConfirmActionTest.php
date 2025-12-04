<?php

namespace Tests\Feature\Domain\Mentor\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Actions\MentorConfirmAction;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class MentorConfirmActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_confirms_mentor_by_setting_status_to_one(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        MentorConfirmAction::execute($mentor);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'status' => 1,
        ]);
    }

    public function test_it_can_confirm_already_confirmed_mentor(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);

        MentorConfirmAction::execute($mentor);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'status' => 1,
        ]);
    }

    public function test_it_only_confirms_specified_mentor(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $mentor1 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        $mentor2 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        MentorConfirmAction::execute($mentor1);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor1->id,
            'status' => 1,
        ]);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor2->id,
            'status' => 0,
        ]);
    }

    public function test_confirmed_mentor_appears_in_confirmed_scope(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        $this->assertCount(0, Mentor::query()->confirmed()->get());
        $this->assertCount(1, Mentor::query()->requested()->get());

        MentorConfirmAction::execute($mentor);

        $this->assertCount(1, Mentor::query()->confirmed()->get());
        $this->assertCount(0, Mentor::query()->requested()->get());
    }
}
