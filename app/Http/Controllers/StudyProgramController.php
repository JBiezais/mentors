<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class StudyProgramController extends Controller
{
    public function index(): Response
    {
        $data = Faculty::query()->with('programs')->get();

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
            'lriCode' => 'required',
            'level' => 'required',
        ]);

        StudyProgram::create($data);

        return Redirect::to('/programs');
    }
    public function edit(){

    }
    public function update(){

    }
    public function destroy($program):RedirectResponse
    {
        StudyProgram::find($program)->delete();

        return Redirect::to(route('programs.index'));
    }
}
