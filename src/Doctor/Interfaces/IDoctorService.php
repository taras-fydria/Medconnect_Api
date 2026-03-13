<?php

namespace App\Doctor\Interfaces;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\User\UserEntity;

interface IDoctorService
{
    public function createNew(CreateDoctorDTO $doctor);

    public function findById(int $id);

    public function update(UpdateDoctorDTO $doctor, UserEntity $user);

    public function delete(int $id);

    public function getAllDoctors(QueryDoctorsDTO $queryDTO);
}
