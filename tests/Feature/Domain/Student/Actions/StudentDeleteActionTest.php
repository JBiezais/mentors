<?php

namespace Tests\Feature\Domain\Student\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Actions\StudentDeleteAction;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class StudentDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_student(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        StudentDeleteAction::execute($student);

        $this->assertSoftDeleted('students', [
            'id' => $student->id,
        ]);
    }

    public function test_it_does_not_affect_other_students(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $student1 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $student2 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        StudentDeleteAction::execute($student1);

        $this->assertSoftDeleted('students', [
            'id' => $student1->id,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student2->id,
            'deleted_at' => null,
        ]);
    }

    public function test_it_does_not_affect_mentor(): void
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

        StudentDeleteAction::execute($student);

        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'deleted_at' => null,
        ]);
    }

    public function test_deleted_student_is_not_in_regular_queries(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $studentId = $student->id;

        StudentDeleteAction::execute($student);

        $this->assertNull(Student::find($studentId));
        $this->assertNotNull(Student::withTrashed()->find($studentId));
    }
}
