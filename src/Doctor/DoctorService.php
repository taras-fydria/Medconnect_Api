<?php

namespace App\Doctor;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\OutputDoctorDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Entity\Doctor;
use App\Doctor\Exception\DoctorNotFoundException;
use App\Doctor\Interfaces\IDoctorRepository;
use App\Doctor\Interfaces\IDoctorService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Shared\DTO\PaginatedResultDTO;

class DoctorService implements IDoctorService
{
    public function __construct(
        readonly private ValidatorInterface $validator,
        readonly private IDoctorRepository  $doctorRepository,
    )
    {
    }

    public function getAllDoctors($queryDTO): PaginatedResultDTO
    {
        return $this->doctorRepository->getAll($queryDTO);
    }

    public function createNew(CreateDoctorDTO $doctor): OutputDoctorDTO
    {
        // TODO: Implement createNew() method.
        throw new \BadMethodCallException('Not implemented');
    }

    public function getById(int $id): OutputDoctorDTO
    {
        $doctor = $this->doctorRepository->get($id);
        if ($doctor === null) {
            throw new DoctorNotFoundException($id);
        }
        return $doctor;
    }

    public function update(UpdateDoctorDTO $doctor): OutputDoctorDTO
    {
        // TODO: Implement update() method.
        throw new \BadMethodCallException('Not implemented');
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        throw new \BadMethodCallException('Not implemented');
    }

}
