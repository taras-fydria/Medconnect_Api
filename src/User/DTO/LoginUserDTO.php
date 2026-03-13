<?php

namespace App\User\DTO;

use OpenApi\Attributes as OA;

class LoginUserDTO
{
    public function __construct(
        #[OA\Property(type: 'string', example: '+79991234567')]
        public readonly string $phone,
        #[OA\Property(type: 'string', example: 'Secret1@')]
        public readonly string $password,
    )
    {
    }
}
