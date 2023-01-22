<?php

namespace App\Http\Controllers;

use App\Http\Requests\MentorRequest;
use App\Models\Faculty;
use App\Models\Mentor;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MentorController extends Controller
{
    public function index():Response
    {
        $programs = StudyProgram::query()->select('id', 'code')->get();

        return Inertia::render('Admin/Mentor',[
            'programs' => $programs
        ]);
    }
    public function create():Response
    {
        $faculties = Faculty::query()->with('programs')->get();
        return Inertia::render('Public/Mentor', [
            'faculties' => $faculties
        ]);
    }
    public function store(MentorRequest $request){
        dd($request);
    }
    public function edit(Mentor $mentor):Response
    {
        return Inertia::render('Admin/EditMentor', $mentor);
    }
    public function update(){

    }
    public function destroy(){

    }
}
