<?php

namespace App\User\DTO;

use Symfony\Component\Validator\Constraints as Assert;


readonly class RegisterUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex(
            pattern: '/^\+[1-9]\d{9,14}$/',
            message: 'Invalid phone number format'
        )]
        public string $phone,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8, max: 16)]
        #[Assert\Regex(
            pattern: '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            message: 'Password must contain uppercase letter, number and special character'
        )]
        public string $password,
    )
    {
    }
}
