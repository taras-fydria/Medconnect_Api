<?php

namespace App\Doctor\Interfaces;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Entity\Doctor;
use App\Shared\Interfaces\IRepositoryFilter;
use App\User\UserEntity;

interface IDoctorRepository extends IRepositoryFilter
{
    public function getByID(int $id): ?Doctor;

    /** @return Doctor[] */
    public function getAll(QueryDoctorsDTO $queryDTO): array;

    public function getTotalCount(QueryDoctorsDTO $queryDTO): int;

    public function saveOne(CreateDoctorDTO $dto, UserEntity $user, bool $flush = true): Doctor;

    public function deleteOne(Doctor $doctor, bool $flush = true): void;

    public function findOneByUserID(int $userId): ?Doctor;

    public function getFirstID(): int;

    public function getByUserID(int $userId): ?Doctor;

    public function updateOne(UpdateDoctorDTO $dto, Doctor $doctor): Doctor;
}
