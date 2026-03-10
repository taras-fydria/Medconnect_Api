<?php

namespace App\User\DTO;

class LoginUserDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly string $password,
    )
    {
    }
}
