<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Faculty;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class StudentsController extends Controller
{
    public function index(): Response
    {
        $programs = StudyProgram::query()->get();
        $students = Student::query()->with('mentor')->get();

        return Inertia::render('Admin/Mentee', [
            'programs' => $programs,
            'students' => $students
        ]);
    }
    public function create(): Response
    {
        $faculties = Faculty::query()->with('programs')->get();
        $mentors = Mentor::all();

        return Inertia::render('Public/Student', [
            'faculties' => $faculties,
            'mentors' => $mentors
        ]);
    }
    public function store(StudentRequest $request):RedirectResponse
    {
        $data = $request->validated();

        Student::create($data);

        return Redirect::route('home');
    }
    public function edit(Student $student){

        $faculties = Faculty::with('programs')->get();
        $mentors = Mentor::all();
        $student = Student::query()->whereId($student->id)->with('mentor')->first();

        return Inertia::render('Admin/EditStudent', [
            'student' => $student,
            'mentors' => $mentors,
            'faculties' => $faculties
        ]);
    }
    public function update(Student $student, Request $request){

        $data = $request->validate([
            'id' => 'required',
            'faculty_id' => 'required|integer',
            'program_id' => 'required|integer',
            'mentor_id' => 'integer',
            'name' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email',
            'comment' => 'nullable',
            'lang' => 'nullable|integer',
        ]);

        Student::find($data['id'])->update($data);

        return Redirect::route('student.edit', $data['id']);
    }
    public function destroy(Student $student){

        $student->delete();

        return Redirect::route('student.index');
    }
}
