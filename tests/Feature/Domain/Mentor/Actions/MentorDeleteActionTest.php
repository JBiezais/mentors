<?php

namespace Tests\Feature\Domain\Mentor\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Actions\MentorDeleteAction;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class MentorDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_mentor(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        MentorDeleteAction::execute($mentor);

        $this->assertSoftDeleted('mentors', [
            'id' => $mentor->id,
        ]);
    }

    public function test_it_nullifies_students_mentor_id_before_deletion(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $student1 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        $student2 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        MentorDeleteAction::execute($mentor);

        $this->assertDatabaseHas('students', [
            'id' => $student1->id,
            'mentor_id' => null,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student2->id,
            'mentor_id' => null,
        ]);
    }

    public function test_it_does_not_affect_students_of_other_mentors(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $mentor1 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $mentor2 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $student1 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor1->id,
        ]);

        $student2 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor2->id,
        ]);

        MentorDeleteAction::execute($mentor1);

        $this->assertDatabaseHas('students', [
            'id' => $student1->id,
            'mentor_id' => null,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student2->id,
            'mentor_id' => $mentor2->id,
        ]);
    }

    public function test_it_does_not_affect_other_mentors(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $mentor1 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $mentor2 = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        MentorDeleteAction::execute($mentor1);

        $this->assertSoftDeleted('mentors', [
            'id' => $mentor1->id,
        ]);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor2->id,
            'deleted_at' => null,
        ]);
    }

    public function test_deleted_mentor_is_not_in_regular_queries(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $mentorId = $mentor->id;

        MentorDeleteAction::execute($mentor);

        $this->assertNull(Mentor::find($mentorId));
        $this->assertNotNull(Mentor::withTrashed()->find($mentorId));
    }
}
