<?php

namespace App\Doctor\Factory;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Entity\Doctor;
use App\User\UserEntity;

class DoctorFactory
{
    public static function fromCreateDTO(CreateDoctorDTO $DTO, UserEntity $user): Doctor
    {
        return new Doctor()
            ->setFirstName($DTO->firstName)
            ->setLastName($DTO->lastName)
            ->setSpecialization($DTO->specialization)
            ->setLicenseNumber($DTO->licenseNumber)
            ->setUser($user);
    }

    public static function fromUpdateDTO(UpdateDoctorDTO $DTO, Doctor $doctor): Doctor
    {
        return $doctor
            ->setFirstName($DTO->firstName)
            ->setLastName($DTO->lastName)
            ->setSpecialization($DTO->specialization)
            ->setLicenseNumber($DTO->licenseNumber);
    }
}
