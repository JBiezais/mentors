<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use src\Domain\Faculty\Actions\FacultyCreateAction;
use src\Domain\Faculty\Actions\FacultyDeleteAction;
use src\Domain\Faculty\Actions\FacultyUpdateAction;
use src\Domain\Faculty\DTO\FacultyData;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Faculty\Requests\FacultyCreateRequest;
use src\Domain\Faculty\Requests\FacultyUpdateRequest;

class FacultyController extends Controller
{
    public function store(FacultyCreateRequest $request): RedirectResponse
    {
        $data = FacultyData::from($request->all());

        FacultyCreateAction::execute($data);

        return Redirect::route('programs.index');
    }
    public function update(Faculty $faculty, FacultyUpdateRequest $request): RedirectResponse
    {
        $data = FacultyData::from($request->all());

        FacultyUpdateAction::execute($faculty, $data);

        return Redirect::route('programs.index');

    }
    public function destroy(Faculty $faculty):RedirectResponse
    {
        FacultyDeleteAction::execute($faculty);

        return Redirect::route('programs.index');
    }
}
