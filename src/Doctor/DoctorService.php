<?php

namespace App\Doctor;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\OutputDoctorDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Entity\Doctor;
use App\Doctor\Exception\DoctorNotFoundException;
use App\Doctor\Exception\DoctorWithUserIdAlreadyExistException;
use App\Doctor\Interfaces\IDoctorRepository;
use App\Doctor\Interfaces\IDoctorService;
use App\User\UserService;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Shared\DTO\PaginatedResultDTO;

class DoctorService implements IDoctorService
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly IDoctorRepository  $doctorRepository,
        private readonly UserService        $userService
    ) {}

    public function getAllDoctors($queryDTO): PaginatedResultDTO
    {
        $items = $this->doctorRepository->getAll($queryDTO);
        $total = $this->doctorRepository->getTotalCount($queryDTO);
        return new PaginatedResultDTO(
            items: $items,
            total: $total,
            limit: $queryDTO->pagination->limit,
            offset: $queryDTO->pagination->offset
        );
    }

    public function createNew(CreateDoctorDTO $dto): OutputDoctorDTO
    {
        $this->validateDTO($dto);

        $existingDoctor = $this->doctorRepository->getByUserID($dto->userId);

        if ($existingDoctor) {
            throw new DoctorWithUserIdAlreadyExistException($dto->userId);
        }

        $user   = $this->userService->getOne($dto->userId);
        $doctor = $this->doctorRepository->saveOne($dto, $user);
        return new OutputDoctorDTO(
            id: $doctor->getId(),
            firstName: $doctor->getFirstName(),
            lastName: $doctor->getLastName(),
            specialization: $doctor->getSpecialization(),
            licenseNumber: $doctor->getLicenseNumber(),
        );
    }

    public function getById(int $id): OutputDoctorDTO
    {
        $doctor = $this->getDoctorOrException($id);

        return new OutputDoctorDTO(
            id: $doctor->getId(),
            firstName: $doctor->getFirstName(),
            lastName: $doctor->getLastName(),
            specialization: $doctor->getSpecialization(),
            licenseNumber: $doctor->getLicenseNumber(),
        );
    }

    public function update(UpdateDoctorDTO $dto): OutputDoctorDTO
    {
        $this->validateDTO($dto);

        $doctor = $this->getDoctorOrException($dto->id);

        $this->doctorRepository->updateOne($dto, $doctor);

        return new OutputDoctorDTO(
            id: $doctor->getId(),
            firstName: $doctor->getFirstName(),
            lastName: $doctor->getLastName(),
            specialization: $doctor->getSpecialization(),
            licenseNumber: $doctor->getLicenseNumber(),
        );
    }

    public function delete(int $id): void
    {
        $doctor = $this->getDoctorOrException($id);
        $this->doctorRepository->deleteOne($doctor);
    }

    private function validateDTO(mixed $dto): void
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ValidatorException($violations);
        }
    }

    private function getDoctorOrException(int $id): Doctor
    {
        $doctor = $this->doctorRepository->getByID($id);

        if ($doctor === null) {
            throw new DoctorNotFoundException($id);
        }

        return $doctor;
    }

}
