<?php

namespace App\Doctor\Interfaces;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\OutputDoctorDTO;
use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Shared\DTO\PaginatedResultDTO;

interface IDoctorService
{
    public function createNew(CreateDoctorDTO $dto): OutputDoctorDTO;

    public function getById(int $id): OutputDoctorDTO;

    public function update(UpdateDoctorDTO $dto): OutputDoctorDTO;

    public function delete(int $id): void;

    public function getAllDoctors(QueryDoctorsDTO $queryDTO): PaginatedResultDTO;
}