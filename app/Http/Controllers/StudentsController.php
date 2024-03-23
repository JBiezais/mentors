<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Actions\MailMentorDataCreateAction;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Shared\Actions\UserNotificationCreateAction;
use src\Domain\Student\Actions\StudentCreateAction;
use src\Domain\Student\Actions\StudentDeleteAction;
use src\Domain\Student\Actions\StudentUpdateAction;
use src\Domain\Student\DTO\StudentCreateData;
use src\Domain\Student\DTO\StudentUpdateData;
use src\Domain\Student\Models\Student;
use src\Domain\Student\Requests\StudentCreateRequest;
use src\Domain\Student\Requests\StudentUpdateRequest;
use src\Domain\User\Models\User;

class StudentsController extends Controller
{
    public function index(): Response
    {
        $request = request();
        $programs = Program::query()->orderBy('title')->get();
        $faculties = Faculty::query()->with('programs')->orderBy('title')->get();
        $students = Student::query()->with('mentor')->filterFromRequest($request)->get();


        return Inertia::render('Admin/Mentee', [
            'programs' => $programs,
            'faculties' => $faculties,
            'students' => $students,
            'keyword' => $request->keyword,
            'type' => $request->type,
            'program' => $request->program,
            'faculty' => $request->faculty,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }
    public function create(): Response
    {
        $faculties = Faculty::query()->with('programs')->get();
        $mentors = Mentor::query()->where('status', 1)->withCount('students')->get();

        return Inertia::render('Public/Student', [
            'faculties' => $faculties,
            'mentors' => $mentors,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }

    public function store(StudentCreateRequest $request):RedirectResponse
    {
        $data = StudentCreateData::from($request->all());

        StudentCreateAction::execute($data);

        UserNotificationCreateAction::execute(
            'Pieteikums nosūtīts',
            'Jūsu pieteikums ir veiksmīgi nosūtīts lūdzu gaidiet turpmāko ziņu e-pastā'
        );

        return Redirect::route('home');
    }
    public function edit(Student $student): Response
    {
        $faculties = Faculty::with('programs')->get();
        $mentors = Mentor::query()->withCount('students')->get();
        $data = Student::query()->where('id', $student->id)->with('mentor')->first();

        return Inertia::render('Admin/EditStudent', [
            'student' => $data,
            'mentors' => $mentors,
            'faculties' => $faculties,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }
    public function update(Student $student, StudentUpdateRequest $request): RedirectResponse
    {
        $data = StudentUpdateData::from($request->all());

        StudentUpdateAction::execute($student, $data);

        return Redirect::route('student.edit', $data['id']);
    }
    public function destroy(Student $student): RedirectResponse
    {
        StudentDeleteAction::execute($student);

        return Redirect::route('student.index');
    }

    public function sendMentorData(Student $student): void
    {
        MailMentorDataCreateAction::execute([$student->id]);
    }
}
