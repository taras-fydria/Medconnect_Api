<?php

namespace App\User\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;


readonly class RegisterUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex(
            pattern: '/^\+[1-9]\d{9,14}$/',
            message: 'Invalid phone number format'
        )]
        #[OA\Property(description: 'E.164 format', type: 'string', example: '+79991234567')]
        public string $phone,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8, max: 16)]
        #[Assert\Regex(
            pattern: '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            message: 'Password must contain uppercase letter, number and special character'
        )]
        #[OA\Property(description: '8–16 chars, uppercase + digit + special char', type: 'string', example: 'Secret1@')]
        public string $password,
    )
    {
    }
}
