<?php
namespace src\Domain\User\DTO;

use Spatie\LaravelData\Data;

class UserCreateData extends Data
{
    public function __construct(
        public string $name,
        public string $password,
        public string $email,
        public string $phone,
    ) {
    }
}
