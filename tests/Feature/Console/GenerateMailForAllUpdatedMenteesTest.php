<?php

namespace Tests\Feature\Console;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class GenerateMailForAllUpdatedMenteesTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_mail_records_for_updated_students_with_mentors(): void
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
            'created_at' => Carbon::now()->subDay(),
            'updated_at' => Carbon::now(),
        ]);

        $this->artisan('mail:mentors')
            ->assertSuccessful();

        $this->assertDatabaseHas('mails', [
            'type' => 'mentorData',
        ]);
        $this->assertDatabaseHas('mails', [
            'type' => 'menteeData',
        ]);
    }

    public function test_command_does_not_include_students_without_mentor(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
            'created_at' => Carbon::now()->subDay(),
            'updated_at' => Carbon::now(),
        ]);

        $this->artisan('mail:mentors')
            ->assertSuccessful();

        $mentorDataMail = Mail::where('type', 'mentorData')->first();
        $this->assertNotNull($mentorDataMail);
        $this->assertEmpty(json_decode($mentorDataMail->student_ids, true));
    }

    public function test_command_does_not_include_non_updated_students(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $now = Carbon::now();
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->artisan('mail:mentors')
            ->assertSuccessful();

        $mentorDataMail = Mail::where('type', 'mentorData')->first();
        $this->assertNotNull($mentorDataMail);
        $this->assertEmpty(json_decode($mentorDataMail->student_ids, true));
    }

    public function test_command_creates_mentor_mail_records_with_mentor_ids(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'created_at' => Carbon::now()->subDay(),
            'updated_at' => Carbon::now(),
        ]);

        $this->artisan('mail:mentors')
            ->assertSuccessful();

        $menteeDataMail = Mail::where('type', 'menteeData')->first();
        $this->assertNotNull($menteeDataMail);
        $this->assertContains($mentor->id, json_decode($menteeDataMail->mentor_ids, true));
    }

    public function test_command_creates_student_mail_records_with_student_ids(): void
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
            'created_at' => Carbon::now()->subDay(),
            'updated_at' => Carbon::now(),
        ]);

        $this->artisan('mail:mentors')
            ->assertSuccessful();

        $mentorDataMail = Mail::where('type', 'mentorData')->first();
        $this->assertNotNull($mentorDataMail);
        $this->assertContains($student->id, json_decode($mentorDataMail->student_ids, true));
    }

    public function test_command_handles_multiple_students_with_same_mentor(): void
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
            'created_at' => Carbon::now()->subDay(),
            'updated_at' => Carbon::now(),
        ]);

        $student2 = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'created_at' => Carbon::now()->subDay(),
            'updated_at' => Carbon::now(),
        ]);

        $this->artisan('mail:mentors')
            ->assertSuccessful();

        $mentorDataMail = Mail::where('type', 'mentorData')->first();
        $studentIds = json_decode($mentorDataMail->student_ids, true);
        $this->assertContains($student1->id, $studentIds);
        $this->assertContains($student2->id, $studentIds);

        $menteeDataMail = Mail::where('type', 'menteeData')->first();
        $mentorIds = json_decode($menteeDataMail->mentor_ids, true);
        $this->assertCount(1, $mentorIds);
    }
}
