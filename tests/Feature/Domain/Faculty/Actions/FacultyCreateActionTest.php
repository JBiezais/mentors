<?php

namespace Tests\Feature\Domain\Faculty\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Actions\FacultyCreateAction;
use src\Domain\Faculty\DTO\FacultyData;
use src\Domain\Faculty\Models\Faculty;
use Tests\TestCase;

class FacultyCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_faculty_with_valid_data(): void
    {
        $data = FacultyData::from([
            'title' => 'Faculty of Engineering',
            'code' => 'FE',
        ]);

        FacultyCreateAction::execute($data);

        $this->assertDatabaseHas('faculties', [
            'title' => 'Faculty of Engineering',
            'code' => 'FE',
        ]);
    }

    public function test_it_creates_faculty_with_all_required_fields(): void
    {
        $data = FacultyData::from([
            'title' => 'Faculty of Science',
            'code' => 'FS',
        ]);

        FacultyCreateAction::execute($data);

        $faculty = Faculty::where('code', 'FS')->first();

        $this->assertNotNull($faculty);
        $this->assertEquals('Faculty of Science', $faculty->title);
        $this->assertEquals('FS', $faculty->code);
    }

    public function test_it_creates_multiple_faculties(): void
    {
        $data1 = FacultyData::from([
            'title' => 'Faculty One',
            'code' => 'F1',
        ]);

        $data2 = FacultyData::from([
            'title' => 'Faculty Two',
            'code' => 'F2',
        ]);

        FacultyCreateAction::execute($data1);
        FacultyCreateAction::execute($data2);

        $this->assertDatabaseCount('faculties', 2);
    }
}
