<?php

namespace App\Doctor\DTO;

use App\Doctor\Specialization;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDoctorDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $id,

        #[Assert\NotBlank]
        public string $firstName,

        #[Assert\NotBlank]
        public string $lastName,

        #[Assert\Enum(Specialization::class)]
        public string $specialization,

        #[Assert\NotBlank]
        public string $licenseNumber,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $userID,
    ) {}
}
