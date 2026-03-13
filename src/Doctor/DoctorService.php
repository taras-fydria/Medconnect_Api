<?php

namespace App\Doctor;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Interfaces\IDoctorService;
use App\User\UserEntity;

class DoctorService implements Interfaces\IDoctorService
{

    public function createNew(CreateDoctorDTO $doctor)
    {
        // TODO: Implement createNew() method.
    }

    public function findById(int $id)
    {
        // TODO: Implement findById() method.
    }

    public function update(UpdateDoctorDTO $doctor, UserEntity $user)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function getAllDoctors(QueryDoctorsDTO $queryDTO)
    {
        // TODO: Implement getAllDoctors() method.
    }
}
