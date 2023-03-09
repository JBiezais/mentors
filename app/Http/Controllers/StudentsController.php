<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Event;
use App\Models\Faculty;
use App\Models\Mail;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class StudentsController extends Controller
{
    public function index(): Response
    {
        $programs = StudyProgram::query()->orderBy('title')->get();
        $faculties = Faculty::query()->with('programs')->orderBy('title')->get();
        $students = Student::query()->with('mentor');

        if(request('keyword') !== null){
            $values = explode(' ', trim(request('keyword')));
            $students = $students->where(function($query) use ($values) {
                $query->orWhereIn('name', $values);
                $query->orWhereIn('lastName', $values);
            });
        }

        if(request('type') !== null){
            switch (request('type')){
                case 'requested':
                    $students = $students->whereNull('mentor_id');
                    break;
                case 'confirmed':
                    $students = $students->whereNotNull('mentor_id');
                    break;
            }
        }

        if(request('faculty') !== null){
            $students = $students->where('faculty_id', request('faculty'));
        }

        if(request('program') !== null){
            $students = $students->where('program_id', request('program'));
        }

        return Inertia::render('Admin/Mentee', [
            'programs' => $programs,
            'faculties' => $faculties,
            'students' => $students->get(),
            'keyword' => request('keyword'),
            'type' => request('type'),
            'program' => request('program'),
            'faculty' => request('faculty'),
        ]);
    }
    public function create(): Response
    {
        $faculties = Faculty::query()->with('programs')->get();
        $mentors = Mentor::query()->where('status', 1)->withCount('students')->get();

        return Inertia::render('Public/Student', [
            'faculties' => $faculties,
            'mentors' => $mentors
        ]);
    }
    public function store(StudentRequest $request):RedirectResponse
    {
        $data = $request->validated();

        $student = Student::create($data);

        Mail::create([
            'mentor_ids' => null,
            'student_ids' => json_encode(array($student->id)),
            'content' => null,
            'type' => 'mentorData'
        ]);

        Session::flash('message', ['title' => 'Pieteikums nosūtīts', 'text' => 'Jūsu pieteikums ir veiksmīgi nosūtīts lūdzu gaidiet turpmāko ziņu e-pastā']);

        return Redirect::route('home');
    }
    public function edit(Student $student){

        $faculties = Faculty::with('programs')->get();
        $mentors = Mentor::query()->withCount('students')->get();
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

    public function sendMentorData(Student $student){
        Mail::create([
            'mentor_ids' => null,
            'student_ids' => json_encode(array($student->id)),
            'content' => null,
            'type' => 'mentorData'
        ]);
    }
}
