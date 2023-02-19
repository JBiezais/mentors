<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class StudyProgramController extends Controller
{
    public function index(): Response
    {
        $data = Faculty::query()->with(['programs' => function($query) {
            $query->withCount('students');
            $query->withCount('mentors');
            $query->with('spotsTotal');
        }])->get();

        return Inertia::render('Admin/Program', [
            'data' => $data
        ]);
    }
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'faculty_id' => 'required',
            'title' => 'required',
            'code' => 'required',
            'level' => 'required',
        ]);

        StudyProgram::create($data);

        return Redirect::to('/programs');
    }
    public function edit(){

    }
    public function update(StudyProgram $studyProgram, Request $request): RedirectResponse
    {

        $data = $request->validate([
            'id' => 'required',
            'faculty_id' => 'required',
            'title' => 'required',
            'code' => 'required',
            'lriCode' => 'required',
            'level' => 'required',
        ]);

        StudyProgram::find($data['id'])->update($data);

        return Redirect::route('programs.index');
    }
    public function destroy($program):RedirectResponse
    {
        StudyProgram::find($program)->delete();

        return Redirect::to(route('programs.index'));
    }
}
