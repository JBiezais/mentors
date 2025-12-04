<?php

namespace Tests\Feature\Domain\Student\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Actions\StudentUpdateAction;
use src\Domain\Student\DTO\StudentUpdateData;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class StudentUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_student_email(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'email' => 'old@example.com',
        ]);

        $data = StudentUpdateData::from([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
            'name' => $student->name,
            'lastName' => $student->lastName,
            'phone' => $student->phone,
            'email' => 'new@example.com',
            'comment' => $student->comment,
            'lang' => $student->lang,
        ]);

        StudentUpdateAction::execute($student, $data);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'email' => 'new@example.com',
        ]);
    }

    public function test_it_updates_all_student_fields(): void
    {
        $faculty1 = Faculty::factory()->create(['code' => 'F1']);
        $faculty2 = Faculty::factory()->create(['code' => 'F2']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty1->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty2->id]);

        $student = Student::factory()->create([
            'faculty_id' => $faculty1->id,
            'program_id' => $program1->id,
            'name' => 'Old Name',
            'lastName' => 'Old Last',
            'phone' => 'old_phone',
            'email' => 'old@example.com',
            'comment' => 'Old comment',
            'lang' => 1,
        ]);

        $data = StudentUpdateData::from([
            'id' => $student->id,
            'faculty_id' => $faculty2->id,
            'program_id' => $program2->id,
            'mentor_id' => null,
            'name' => 'New Name',
            'lastName' => 'New Last',
            'phone' => 'new_phone',
            'email' => 'new@example.com',
            'comment' => 'New comment',
            'lang' => 2,
        ]);

        StudentUpdateAction::execute($student, $data);

        $student->refresh();

        $this->assertEquals($faculty2->id, $student->faculty_id);
        $this->assertEquals($program2->id, $student->program_id);
        $this->assertEquals('New Name', $student->name);
        $this->assertEquals('New Last', $student->lastName);
        $this->assertEquals('new_phone', $student->phone);
        $this->assertEquals('new@example.com', $student->email);
        $this->assertEquals('New comment', $student->comment);
        $this->assertEquals(2, $student->lang);
    }

    public function test_it_creates_mail_records_when_mentor_is_assigned(): void
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
            'mentor_id' => null,
        ]);

        $data = StudentUpdateData::from([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'name' => $student->name,
            'lastName' => $student->lastName,
            'phone' => $student->phone,
            'email' => $student->email,
            'comment' => $student->comment,
            'lang' => $student->lang,
        ]);

        StudentUpdateAction::execute($student, $data);

        $this->assertDatabaseHas('mails', [
            'type' => 'mentorData',
        ]);

        $this->assertDatabaseHas('mails', [
            'type' => 'menteeData',
        ]);
    }

    public function test_it_does_not_create_mail_records_when_mentor_unchanged(): void
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

        $data = StudentUpdateData::from([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'name' => 'Updated Name',
            'lastName' => $student->lastName,
            'phone' => $student->phone,
            'email' => $student->email,
            'comment' => $student->comment,
            'lang' => $student->lang,
        ]);

        StudentUpdateAction::execute($student, $data);

        $this->assertDatabaseMissing('mails', [
            'type' => 'mentorData',
        ]);
    }

    public function test_it_only_updates_specified_student(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $student1 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'email' => 'student1@example.com',
        ]);

        $student2 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'email' => 'student2@example.com',
        ]);

        $data = StudentUpdateData::from([
            'id' => $student1->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
            'name' => $student1->name,
            'lastName' => $student1->lastName,
            'phone' => $student1->phone,
            'email' => 'updated@example.com',
            'comment' => $student1->comment,
            'lang' => $student1->lang,
        ]);

        StudentUpdateAction::execute($student1, $data);

        $this->assertDatabaseHas('students', [
            'id' => $student1->id,
            'email' => 'updated@example.com',
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student2->id,
            'email' => 'student2@example.com',
        ]);
    }
}
