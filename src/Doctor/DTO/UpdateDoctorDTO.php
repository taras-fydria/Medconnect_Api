<?php

namespace App\Doctor\DTO;

use App\Doctor\Specialization;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(required: ['firstName', 'lastName', 'licenseNumber', 'specialization', 'userID'])]
class UpdateDoctorDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        #[OA\Property(type: 'integer', example: 1)]
        public int    $id,

        #[Assert\NotBlank]
        #[OA\Property(type: 'string', example: 'Ivan')]
        public string $firstName,

        #[Assert\NotBlank]
        #[OA\Property(type: 'string', example: 'Petrov')]
        public string $lastName,

        #[Assert\Enum(Specialization::class)]
        #[OA\Property(type: 'string', enum: ['General Practice', 'General Surgery', 'Cardiology', 'Neurology'], example: 'Cardiology')]
        public string $specialization,

        #[Assert\NotBlank]
        #[OA\Property(type: 'string', example: 'LIC-12345')]
        public string $licenseNumber,

        #[Assert\NotBlank]
        #[Assert\Positive]
        #[OA\Property(type: 'integer', example: 1)]
        public int    $userID,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            firstName: $data['firstName'] ?? '',
            lastName: $data['lastName'] ?? '',
            specialization: $data['specialization'] ?? '',
            licenseNumber: $data['licenseNumber'] ?? '',
            userID: $data['userID'] ?? 0
        );
    }
}
