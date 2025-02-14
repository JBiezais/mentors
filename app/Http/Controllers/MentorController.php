<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use src\Domain\Config\Models\Config;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Actions\MailMenteeDataCreateAction;
use src\Domain\Mail\Actions\MailVerificationPassedCreateAction;
use src\Domain\Mentor\Actions\MentorConfirmAction;
use src\Domain\Mentor\Actions\MentorCreateAction;
use src\Domain\Mentor\Actions\MentorDeleteAction;
use src\Domain\Mentor\Actions\MentorRemoveMenteesAction;
use src\Domain\Mentor\Actions\MentorUpdateAction;
use src\Domain\Mentor\DTO\MentorCreateData;
use src\Domain\Mentor\DTO\MentorUpdateData;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Mentor\Requests\MentorCreateRequest;
use src\Domain\Mentor\Requests\MentorUpdateRequest;
use src\Domain\Program\Models\Program;
use src\Domain\Shared\Actions\UserNotificationCreateAction;
use src\Domain\User\Models\User;

class MentorController extends Controller
{
    public function index(): Response
    {
        $request = request();
        $programs = Program::query()->select('id', 'title', 'code')->orderBy('title')->get();
        $faculties = Faculty::query()->with('programs')->orderBy('title')->get();
        $mentors = Mentor::query()->with('students')->filterFromRequest($request)->get();

        return Inertia::render('Admin/Mentor',[
            'programs' => $programs,
            'mentors' => $mentors,
            'faculties' => $faculties,
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
        $configs = Config::query()->whereIn('type', ['color', 'background'])->select(['type', 'value'])->get();

        return Inertia::render('Public/Mentor', [
            'color' => $configs->where('type', 'color')->first()?->value,
            'background' => $configs->where('type', 'background')->first()?->value,
            'faculties' => $faculties,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }

    public function store(MentorCreateRequest $request): RedirectResponse
    {
        $data = MentorCreateData::fromRequest($request);

        MentorCreateAction::execute($data, $request->file('img'));

        UserNotificationCreateAction::execute(
            'Pieteikums nosūtīts',
            'Jūsu pieteikums ir veiksmīgi nosūtīts lūdzu gaidiet turpmāko ziņu e-pastā'
        );

        return Redirect::route('home');
    }

    public function edit(Mentor $mentor): Response
    {
        $faculties = Faculty::query()->with('programs')->get();
        $programs = Program::query()->select('id', 'title', 'code')->get();
        $data = Mentor::query()->with('students')->where('id', $mentor->id)->first();

        return Inertia::render('Admin/EditMentor', [
            'mentor' => $data,
            'faculties' => $faculties,
            'programs' => $programs,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }

    public function update(MentorUpdateRequest $request, Mentor $mentor): void
    {
        $data = MentorUpdateData::from($request->all());

        MentorUpdateAction::execute($mentor, $data);
    }

    public function destroy(Mentor $mentor): RedirectResponse
    {
        MentorDeleteAction::execute($mentor);

        return Redirect::route('mentor.index');
    }

    public function removeMentees(Mentor $mentor, Request $request): RedirectResponse
    {
        MentorRemoveMenteesAction::execute($mentor, $request->ids);

        return Redirect::route('mentor.edit', $mentor->id);
    }

    public function confirmMentor(Mentor $mentor): void
    {
        MentorConfirmAction::execute($mentor);

        MailVerificationPassedCreateAction::execute([$mentor->id]);
    }

    public function sendMenteeData(Mentor $mentor): RedirectResponse
    {
        MailMenteeDataCreateAction::execute([$mentor->id]);

        return Redirect::route('home');
    }
}
