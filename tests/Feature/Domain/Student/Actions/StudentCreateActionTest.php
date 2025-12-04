<?php

namespace Tests\Feature\Domain\Student\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Actions\StudentCreateAction;
use src\Domain\Student\DTO\StudentCreateData;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class StudentCreateActionTest extends TestCase
{
    use RefreshDatabase;

    private function createStudentData(Faculty $faculty, Program $program, ?Mentor $mentor = null): StudentCreateData
    {
        return StudentCreateData::from([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor?->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane.doe@example.com',
            'comment' => 'Some comment',
            'lang' => '1',
            'privacy' => true,
        ]);
    }

    public function test_it_creates_student_with_valid_data(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createStudentData($faculty, $program);

        StudentCreateAction::execute($data);

        $this->assertDatabaseHas('students', [
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'email' => 'jane.doe@example.com',
        ]);
    }

    public function test_it_creates_student_with_mentor(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $data = $this->createStudentData($faculty, $program, $mentor);

        StudentCreateAction::execute($data);

        $this->assertDatabaseHas('students', [
            'email' => 'jane.doe@example.com',
            'mentor_id' => $mentor->id,
        ]);
    }

    public function test_it_creates_student_without_mentor(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createStudentData($faculty, $program);

        StudentCreateAction::execute($data);

        $this->assertDatabaseHas('students', [
            'email' => 'jane.doe@example.com',
            'mentor_id' => null,
        ]);
    }

    public function test_it_creates_mail_mentor_data_record(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createStudentData($faculty, $program);

        StudentCreateAction::execute($data);

        $student = Student::where('email', 'jane.doe@example.com')->first();

        $this->assertDatabaseHas('mails', [
            'type' => 'mentorData',
        ]);

        $mail = Mail::where('type', 'mentorData')->first();
        $this->assertContains($student->id, $mail->student_ids);
    }

    public function test_it_stores_all_student_fields(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data = $this->createStudentData($faculty, $program);

        StudentCreateAction::execute($data);

        $student = Student::where('email', 'jane.doe@example.com')->first();

        $this->assertEquals($faculty->id, $student->faculty_id);
        $this->assertEquals($program->id, $student->program_id);
        $this->assertEquals('Jane', $student->name);
        $this->assertEquals('Doe', $student->lastName);
        $this->assertEquals('+37120000000', $student->phone);
        $this->assertEquals('Some comment', $student->comment);
        $this->assertEquals('1', $student->lang);
        $this->assertTrue((bool)$student->privacy);
    }

    public function test_it_creates_multiple_students(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $data1 = StudentCreateData::from([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
            'name' => 'Student',
            'lastName' => 'One',
            'phone' => '11111111',
            'email' => 'student1@example.com',
            'comment' => null,
            'lang' => '1',
            'privacy' => true,
        ]);

        $data2 = StudentCreateData::from([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
            'name' => 'Student',
            'lastName' => 'Two',
            'phone' => '22222222',
            'email' => 'student2@example.com',
            'comment' => null,
            'lang' => '2',
            'privacy' => true,
        ]);

        StudentCreateAction::execute($data1);
        StudentCreateAction::execute($data2);

        $this->assertDatabaseCount('students', 2);
    }
}
