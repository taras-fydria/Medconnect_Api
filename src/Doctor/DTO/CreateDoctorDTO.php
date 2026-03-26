<?php

namespace App\Doctor\DTO;

use App\Doctor\Specialization;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDoctorDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[OA\Property(type: 'string', example: 'Ivan')]
        public string $firstName,

        #[Assert\NotBlank]
        #[OA\Property(type: 'string', example: 'Petrov')]
        public string $lastName,

        #[Assert\NotBlank]
        #[OA\Property(type: 'string', example: 'LIC-12345')]
        public string $licenseNumber,

        #[Assert\NotBlank]
        #[Assert\Enum(Specialization::class)]
        #[OA\Property(type: 'string', enum: ['General Practice', 'General Surgery', 'Cardiology', 'Neurology'], example: 'Cardiology')]
        public string $specialization,

        #[Assert\NotBlank]
        #[Assert\Positive]
        #[OA\Property(type: 'integer', example: 1)]
        public int    $userId,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['firstName'] ?? '',
            lastName: $data['lastName'] ?? '',
            licenseNumber: $data['licenseNumber'] ?? '',
            specialization: $data['specialization'] ?? '',
            userId: $data['userID'] ?? 0,
        );
    }
}
