<?php

namespace App\Tests\Doctor;

use App\DataFixtures\DoctorFixture;
use App\DataFixtures\UserFixture;
use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\OutputDoctorDTO;
use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Exception\DoctorNotFoundException;
use App\Doctor\Exception\DoctorWithLicenseNumberAlreadyExistException;
use App\Doctor\Exception\DoctorWithUserIdAlreadyExistException;
use App\Doctor\Interfaces\IDoctorRepository;
use App\Doctor\Interfaces\IDoctorService;
use App\Doctor\Specialization;
use App\Shared\DTO\PaginatedResultDTO;
use App\User\UserEntity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DoctorServiceTest extends KernelTestCase
{
    private IDoctorService $service;
    private int $doctorId;
    private int $userId;
    private int $doctorUserId;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->service = static::getContainer()->get(IDoctorService::class);

        /** @var UserProviderInterface $userProvider */
        $userProvider = static::getContainer()->get(UserProviderInterface::class);
        /** @var IDoctorRepository $doctorRepo */
        $doctorRepo = static::getContainer()->get(IDoctorRepository::class);

        // UserEntity is a security entity — load via framework abstraction, not User module internals
        /** @var UserEntity $user */
        $user         = $userProvider->loadUserByIdentifier(UserFixture::PHONE);
        $this->userId = $user->getId();

        /** @var UserEntity $knownDoctorUser */
        $knownDoctorUser    = $userProvider->loadUserByIdentifier(DoctorFixture::PHONE);
        $this->doctorUserId = $knownDoctorUser->getId();
        $this->doctorId     = $doctorRepo->findOneByUserID($this->doctorUserId)->getId();
    }

    private function createDoctorDTO(): CreateDoctorDTO
    {
        echo '<pre>';
        var_dump($this->userId);
        echo '</pre>';
        return new CreateDoctorDTO(
            firstName: 'John',
            lastName: 'Doe',
            licenseNumber: '987698765987tudiwfcasd',
            specialization: Specialization::GeneralPractice->value,
            userId: $this->userId,
        );
    }

    private function updateDoctorDTO(): UpdateDoctorDTO
    {
        return new UpdateDoctorDTO(
            id: $this->doctorId,
            firstName: 'John',
            lastName: 'Doe',
            specialization: Specialization::GeneralPractice->value,
            licenseNumber: '987698765987tudiwfcasd',
            userID: $this->doctorUserId,
        );
    }

    public function testGetAll(): void
    {
        $result = $this->service->getAllDoctors(new QueryDoctorsDTO());

        $this->assertInstanceOf(PaginatedResultDTO::class, $result);
        $this->assertIsArray($result->items);
        foreach ($result->items as $doctor) {
            $this->assertInstanceOf(OutputDoctorDTO::class, $doctor);
        }
        $this->assertGreaterThan(0, $result->total);
        $this->assertCount($result->limit, $result->items);
    }

    public function testGetDoctorByID(): void
    {
        $result = $this->service->getById($this->doctorId);

        $this->assertInstanceOf(OutputDoctorDTO::class, $result);
        $this->assertSame($this->doctorId, $result->id);
    }

    public function testGetDoctorByUnexistingId(): void
    {
        $this->expectException(DoctorNotFoundException::class);
        $this->service->getById(0);
    }

    public function testCreateDoctor(): void
    {
        $result = $this->service->createNew($this->createDoctorDTO());

        $this->assertInstanceOf(OutputDoctorDTO::class, $result);
    }

    public function testCreateDoctorWithAlreadyExistingDoctor(): void
    {
        $dto = new CreateDoctorDTO(
            firstName: 'Jane',
            lastName: 'Doe',
            licenseNumber: 'LIC-99999',
            specialization: Specialization::GeneralPractice->value,
            userId: $this->doctorUserId,
        );

        $this->expectException(DoctorWithUserIdAlreadyExistException::class);
        $this->service->createNew($dto);
    }

    public function testCreateDoctorWithExistingLicenseNumber(): void
    {
        $dto = new CreateDoctorDTO(
            firstName: 'Jane',
            lastName: 'Doe',
            licenseNumber: DoctorFixture::LICENSE,
            specialization: Specialization::GeneralPractice->value,
            userId: $this->userId,
        );

        $this->expectException(DoctorWithLicenseNumberAlreadyExistException::class);
        $this->service->createNew($dto);
    }

    public function testUpdateDoctorWithExistingLicenseNumber(): void
    {
        $second = $this->service->createNew($this->createDoctorDTO());

        $dto = new UpdateDoctorDTO(
            id: $second->id,
            firstName: 'Jane',
            lastName: 'Doe',
            specialization: Specialization::GeneralPractice->value,
            licenseNumber: DoctorFixture::LICENSE,
            userID: $this->userId,
        );

        $this->expectException(DoctorWithLicenseNumberAlreadyExistException::class);
        $this->service->update($dto);
    }

    public function testUpdate(): void
    {
        $dto = $this->updateDoctorDTO();

        $result = $this->service->update($dto);

        $this->assertInstanceOf(OutputDoctorDTO::class, $result);
        $this->assertSame($this->doctorId, $result->id);
        $this->assertSame($dto->firstName, $result->firstName);
        $this->assertSame($dto->lastName, $result->lastName);
        $this->assertSame($dto->licenseNumber, $result->licenseNumber);
    }

    public function testUpdateUnexistingDoctor(): void
    {
        $dto = new UpdateDoctorDTO(
            id: 10000000,
            firstName: 'John',
            lastName: 'Doe',
            specialization: Specialization::GeneralPractice->value,
            licenseNumber: 'LIC-00000',
            userID: (string)$this->doctorUserId,
        );

        $this->expectException(DoctorNotFoundException::class);
        $this->service->update($dto);
    }

    public function testDeleteDoctorById(): void
    {
        $this->service->delete($this->doctorId);

        $this->expectException(DoctorNotFoundException::class);
        $this->service->getById($this->doctorId);
    }

    public function testDeleteDoctorByUnexistingId(): void
    {
        $this->expectException(DoctorNotFoundException::class);
        $this->service->delete(0);
    }
}
