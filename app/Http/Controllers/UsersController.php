<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use src\Domain\User\Actions\UserCreateAction;
use src\Domain\User\Actions\UserDeleteAction;
use src\Domain\User\Actions\UserUpdateAction;
use src\Domain\User\DTO\UserCreateData;
use src\Domain\User\Models\User;
use src\Domain\User\Requests\UserCreateRequest;

class UsersController extends Controller
{
    public function index(): Response
    {
        $users = User::query()->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Users', [
            'users' => $users,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $data = UserCreateData::from($request->all());

        UserCreateAction::execute($data);

        return Redirect::route('users.index');
    }

    public function update(User $user): RedirectResponse
    {
        UserUpdateAction::execute($user);

        return Redirect::route('users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        UserDeleteAction::execute($user);

        return Redirect::route('users.index');
    }
}
