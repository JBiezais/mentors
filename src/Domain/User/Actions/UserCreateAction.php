<?php

namespace src\Domain\User\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\User\DTO\UserCreateData;
use src\Domain\User\Models\User;

class UserCreateAction extends Model
{
    public static function execute(UserCreateData $data): void
    {
        $data->password = bcrypt($data->password);

        User::query()->create($data->toArray());
    }
}
