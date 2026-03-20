<?php

namespace App\Doctor\Interfaces;

use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\Entity\Doctor;
use App\Shared\Interfaces\IRepositoryFilter;

interface IDoctorRepository extends IRepositoryFilter
{
    public function get(int $id): ?Doctor;

    /** @return Doctor[] */
    public function getAll(QueryDoctorsDTO $queryDTO): array;

    public function getTotalCount(QueryDoctorsDTO $queryDTO): int;

    public function saveOne(Doctor $doctor, bool $flush = true): void;

    public function deleteOne(Doctor $doctor, bool $flush = true): void;

    public function findByUserId(int $userId): ?Doctor;

    public function getFirstDoctorId(): int;
}
