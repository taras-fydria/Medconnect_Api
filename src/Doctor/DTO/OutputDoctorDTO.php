<?php

namespace App\Doctor\DTO;

use App\Doctor\Specialization;
use JsonSerializable;
use OpenApi\Attributes as OA;

class OutputDoctorDTO implements JsonSerializable
{
    public function __construct(
        #[OA\Property(type: 'integer', example: 1)]
        public int            $id,
        #[OA\Property(type: 'string', example: 'Ivan')]
        public string         $firstName,
        #[OA\Property(type: 'string', example: 'Petrov')]
        public string         $lastName,
        #[OA\Property(type: 'string', enum: ['General Practice', 'General Surgery', 'Cardiology', 'Neurology'], example: 'Cardiology')]
        public Specialization $specialization,
        #[OA\Property(type: 'string', example: 'LIC-12345')]
        public string         $licenseNumber,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id'             => $this->id,
            'firstName'      => $this->firstName,
            'lastName'       => $this->lastName,
            'specialization' => $this->specialization->value,
            'licenseNumber'  => $this->licenseNumber,
        ];
    }
}
