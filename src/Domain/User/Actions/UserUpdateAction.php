<?php

namespace src\Domain\User\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\User\DTO\UserCreateData;
use src\Domain\User\Models\User;

class UserUpdateAction extends Model
{
    public static function execute(User $user): void
    {
        $user->update([
            'use' => true
        ]);

        User::query()
            ->where('use', true)
            ->whereNot('id', $user->id)
            ->update([
                'use' => false
            ]);
    }
}
