<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Public/Student');
    }
    public function create(): Response
    {
        $faculties = Faculty::query()->with('programs')->get();

        return Inertia::render('Public/Student', [
            'faculties' => $faculties
        ]);
    }
    public function store(Request $request){
        dd($request);
    }
    public function update(){

    }
    public function destroy(){

    }
}
