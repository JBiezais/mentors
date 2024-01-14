<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Actions\ProgramCreateAction;
use src\Domain\Program\Actions\ProgramDeleteAction;
use src\Domain\Program\Actions\ProgramUpdateAction;
use src\Domain\Program\DTO\ProgramCreateData;
use src\Domain\Program\DTO\ProgramUpdateData;
use src\Domain\Program\Models\Program;
use src\Domain\Program\Requests\ProgramCreateRequest;
use src\Domain\Program\Requests\ProgramUpdateRequest;

class ProgramController extends Controller
{
    public function index(): Response
    {
        $data = Faculty::query()->with(['programs' => function($query) {
            $query->withCount('students');
            $query->withCount('mentors');
            $query->with('spotsTotal');
        }])->orderBy('title')->get();

        return Inertia::render('Admin/Program', [
            'data' => $data
        ]);
    }
    public function store(ProgramCreateRequest $request): RedirectResponse
    {
        $data = ProgramCreateData::from($request->all());

        ProgramCreateAction::execute($data);

        return Redirect::to('/programs');
    }

    public function update(Program $program, ProgramUpdateRequest $request): RedirectResponse
    {
        $data = ProgramUpdateData::from($request->all());

        ProgramUpdateAction::execute($program, $data);

        return Redirect::route('programs.index');
    }
    public function destroy(Program $program): RedirectResponse
    {
        ProgramDeleteAction::execute($program);

        return Redirect::to(route('programs.index'));
    }
}
