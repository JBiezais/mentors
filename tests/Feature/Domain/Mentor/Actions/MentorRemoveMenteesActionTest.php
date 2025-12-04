<?php

namespace Tests\Feature\Domain\Mentor\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Actions\MentorRemoveMenteesAction;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class MentorRemoveMenteesActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_removes_specified_mentees_from_mentor(): void
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

        MentorRemoveMenteesAction::execute($mentor, [$student1->id]);

        $this->assertDatabaseHas('students', [
            'id' => $student1->id,
            'mentor_id' => null,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student2->id,
            'mentor_id' => $mentor->id,
        ]);
    }

    public function test_it_removes_multiple_mentees_from_mentor(): void
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

        $student3 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        MentorRemoveMenteesAction::execute($mentor, [$student1->id, $student2->id]);

        $this->assertDatabaseHas('students', [
            'id' => $student1->id,
            'mentor_id' => null,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student2->id,
            'mentor_id' => null,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student3->id,
            'mentor_id' => $mentor->id,
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

        MentorRemoveMenteesAction::execute($mentor1, [$student1->id, $student2->id]);

        $this->assertDatabaseHas('students', [
            'id' => $student1->id,
            'mentor_id' => null,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student2->id,
            'mentor_id' => $mentor2->id,
        ]);
    }

    public function test_it_handles_empty_mentees_array(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        MentorRemoveMenteesAction::execute($mentor, []);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'mentor_id' => $mentor->id,
        ]);
    }

    public function test_it_handles_non_existent_student_ids(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        MentorRemoveMenteesAction::execute($mentor, [999, 1000]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'mentor_id' => $mentor->id,
        ]);
    }
}
