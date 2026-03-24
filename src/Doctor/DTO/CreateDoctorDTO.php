<?php

namespace App\Doctor\DTO;

use App\Doctor\Specialization;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDoctorDTO
{

    public function __construct(
        #[Assert\NotBlank]
        public string $firstName,

        #[Assert\NotBlank]
        public string $lastName,

        #[Assert\NotBlank]
        public string $licenseNumber,

        #[Assert\NotBlank]
        #[Assert\Enum(Specialization::class)]
        public string $specialization,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $userId,
    )
    {
    }
}
