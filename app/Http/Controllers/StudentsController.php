<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Faculty;
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

        return Inertia::render('Public/Student', [
            'faculties' => $faculties
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

        return Inertia::render('Admin/EditStudent', [
            'student' => $student,
            'faculties' => $faculties
        ]);
    }
    public function update(){

    }
    public function destroy(Student $student){

        $student->delete();

        return Redirect::route('student.index');
    }
}
