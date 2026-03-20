<?php

namespace App\Doctor\DTO;

use App\Doctor\Specialization;
use JsonSerializable;

class OutputDoctorDTO implements JsonSerializable
{
    public function __construct(
        public int           $id,
        public string        $phone,
        public string        $firstName,
        public string        $lastName,
        public Specialization $specialization,
        public string        $licenseNumber,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id'             => $this->id,
            'phone'          => $this->phone,
            'firstName'      => $this->firstName,
            'lastName'       => $this->lastName,
            'specialization' => $this->specialization->value,
            'licenseNumber'  => $this->licenseNumber,
        ];
    }
}