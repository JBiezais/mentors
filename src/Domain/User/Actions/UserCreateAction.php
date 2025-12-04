<?php

namespace src\Domain\User\Actions;

use src\Domain\User\DTO\UserCreateData;
use src\Domain\User\Models\User;

class UserCreateAction
{
    public static function execute(UserCreateData $data): void
    {
        $data->password = bcrypt($data->password);

        User::query()->create($data->toArray());
    }
}
