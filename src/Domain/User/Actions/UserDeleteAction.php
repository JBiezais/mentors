<?php

namespace src\Domain\User\Actions;

use src\Domain\User\Models\User;

class UserDeleteAction
{
    public static function execute(User $user): void
    {
        if($user->use){
            User::query()
                ->whereNot('id', $user->id)
                ->latest('updated_at')
                ->take(1)
                ->update([
                    'use' => true
                ]);
        }

        $user->delete();
    }
}
