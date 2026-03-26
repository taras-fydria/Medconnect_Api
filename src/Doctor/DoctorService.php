<?php

namespace App\Doctor;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\OutputDoctorDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Entity\Doctor;
use App\Doctor\Exception\DoctorNotFoundException;
use App\Doctor\Exception\DoctorWithLicenseNumberAlreadyExistException;
use App\Doctor\Exception\DoctorWithUserIdAlreadyExistException;
use App\Doctor\Factory\DoctorFactory;
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
    )
    {
    }

    public function getAllDoctors($queryDTO): PaginatedResultDTO
    {
        $resultItems = $this->doctorRepository->getAll($queryDTO);
        $outputItems = array_map(fn($doctor) => new OutputDoctorDTO(
            id: $doctor->getId(),
            firstName: $doctor->getFirstName(),
            lastName: $doctor->getLastName(),
            specialization: $doctor->getSpecialization(),
            licenseNumber: $doctor->getLicenseNumber()
        ), $resultItems);

        $total       = $this->doctorRepository->getTotalCount($queryDTO);

        return new PaginatedResultDTO(
            items: $outputItems,
            total: $total,
            limit: $queryDTO->pagination->limit,
            offset: $queryDTO->pagination->offset
        );
    }

    public function createNew(CreateDoctorDTO $dto): OutputDoctorDTO
    {
        $this->validateDTO($dto);

        $doctors          = $this->doctorRepository->findConflicts($dto->userId, $dto->licenseNumber);
        $isDoctorExist    = array_find($doctors, fn(Doctor $doctor) => $doctor->getUser()->getId() === $dto->userId);
        $isLicenseIsInUse = array_find($doctors, fn($doctor) => $doctor->getLicenseNumber() === $dto->licenseNumber);

        if ($isDoctorExist) {
            throw new DoctorWithUserIdAlreadyExistException($dto->userId);
        }

        if ($isLicenseIsInUse) {
            throw new DoctorWithLicenseNumberAlreadyExistException($dto->licenseNumber);
        }

        $user   = $this->userService->getOne($dto->userId);
        $doctor = DoctorFactory::fromCreateDTO($dto, $user);

        $doctor->setUser($user);

        $doctor = $this->doctorRepository->saveOne($doctor);

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

        $existingDoctor = $this->getDoctorOrException($dto->id);

        if ($existingDoctor->getLicenseNumber() !== $dto->licenseNumber
            && $this->doctorRepository->existsByLicenseNumberExcluding($dto->licenseNumber, $dto->id)) {
            throw new DoctorWithLicenseNumberAlreadyExistException($dto->licenseNumber);
        }

        $updatedDoctor = DoctorFactory::fromUpdateDTO($dto, $existingDoctor);

        $this->doctorRepository->saveOne($updatedDoctor);

        return new OutputDoctorDTO(
            id: $existingDoctor->getId(),
            firstName: $existingDoctor->getFirstName(),
            lastName: $existingDoctor->getLastName(),
            specialization: $existingDoctor->getSpecialization(),
            licenseNumber: $existingDoctor->getLicenseNumber(),
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
        $doctor = $this->doctorRepository->findByDoctorID($id);

        if ($doctor === null) {
            throw new DoctorNotFoundException($id);
        }

        return $doctor;
    }

}
