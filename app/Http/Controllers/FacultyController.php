<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class FacultyController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Home');
    }
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required',
            'code' => 'required'
        ]);

        Faculty::create($data);

        return Redirect::to(route('programs.index'));
    }
    public function update(Faculty $faculty, Request $request){
        $data = $request->validate([
            'id' => 'required',
            'title' => 'required'
        ]);

        $faculty->update($data);

        return Redirect::route('programs.index');

    }
    public function destroy($faculty):RedirectResponse
    {
        Faculty::find($faculty)->delete();

        return Redirect::to(route('programs.index'));
    }
}
