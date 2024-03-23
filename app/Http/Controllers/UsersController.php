<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use src\Domain\User\Actions\UserCreateAction;
use src\Domain\User\DTO\UserCreateData;
use src\Domain\User\Models\User;
use src\Domain\User\Requests\UserCreateRequest;

class UsersController extends Controller
{
    public function index(): Response
    {
        $users = User::query()->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $data = UserCreateData::from($request->all());

        UserCreateAction::execute($data);

        return Redirect::route('users.index');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $user->update([
            'use' => true
        ]);

        return Redirect::route('users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return Redirect::route('users.index');
    }
}
