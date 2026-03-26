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
    public function findByDoctorID(int $id): ?Doctor;

    /** @return Doctor[] */
    public function getAll(QueryDoctorsDTO $queryDTO): array;

    public function getTotalCount(QueryDoctorsDTO $queryDTO): int;

    public function saveOne(Doctor $doctor, bool $flush = true): Doctor;

    public function deleteOne(Doctor $doctor, bool $flush = true): void;

    public function findOneByUserID(int $userId): ?Doctor;

    public function getFirstID(): int;

    /** @return Doctor[] */
    public function findConflicts(int $userId, string $licenseNumber): array;

    public function existsByLicenseNumberExcluding(string $licenseNumber, int $excludeId): bool;
}
