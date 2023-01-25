<?php

namespace App\Http\Controllers;

use App\Actions\UploadFileAction;
use App\Http\Requests\MentorRequest;
use App\Models\Faculty;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class MentorController extends Controller
{
    public function index():Response
    {
        $programs = StudyProgram::query()->select('id', 'title', 'code')->get();
        $faculties = Faculty::all();
        $mentors = Mentor::all();

        return Inertia::render('Admin/Mentor',[
            'programs' => $programs,
            'mentors' => $mentors,
            'faculties' => $faculties
        ]);
    }
    public function create():Response
    {
        $faculties = Faculty::query()->with('programs')->get();
        return Inertia::render('Public/Mentor', [
            'faculties' => $faculties
        ]);
    }
    public function store(MentorRequest $request, UploadFileAction $uploadFileAction): RedirectResponse
    {
        $data = $request->validated();

        if($request->hasFile('img')){
            $data['img'] = $uploadFileAction->upload($request->file('img'));
        }

        Mentor::create($data);

        return Redirect::route('home');
    }
    public function edit(Mentor $mentor):Response
    {
        $faculties = Faculty::query()->with('programs')->get();
        $programs = StudyProgram::query()->select('id', 'title', 'code')->get();
        $data = Mentor::query()->whereId($mentor->id)->with('students')->first();

        return Inertia::render('Admin/EditMentor', [
            'mentor' => $data,
            'faculties' => $faculties,
            'programs' => $programs,
        ]);
    }
    public function update(Request $request, Mentor $mentor){
        $data = $request->validate([
            'id' => 'required',
            'faculty_id' => 'required|integer',
            'program_id' => 'required|integer',
            'phone' => 'required',
            'email' => 'required|email',
            'year' => 'required|integer',
            'about' => 'required',
            'why' => 'required',
            'lv' => 'nullable|boolean',
            'ru' => 'nullable|boolean',
            'en' => 'nullable|boolean',
        ]);

        Mentor::find($data['id'])->update($data);
    }
    public function destroy(Mentor $mentor): RedirectResponse
    {
        Student::query()->where('mentor_id', $mentor->id)->update(['mentor_id' => null]);
        Mentor::find($mentor->id)->delete();

        return Redirect::route('mentor.index');
    }
    public function removeMentees(Mentor $mentor): RedirectResponse
    {
        Student::query()->where('mentor_id', $mentor->id)->update(['mentor_id' => null]);

        return Redirect::route('mentor.edit', $mentor->id);
    }
}
