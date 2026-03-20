<?php

namespace App\Tests\Doctor;

use App\Doctor\DTO\OutputDoctorDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Entity\Doctor;
use App\Doctor\Exception\DoctorNotFoundException;
use App\Doctor\Interfaces\IDoctorRepository;
use App\Doctor\Interfaces\IDoctorService;
use App\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctorServiceTest extends KernelTestCase
{
    private IDoctorService $service;


    protected function getDoctorId(): int
    {
        self::bootKernel();
        /** @var IDoctorRepository $doctorRepository */
        $doctorRepository = static::getContainer()->get(IDoctorRepository::class);
        return $doctorRepository->getFirstDoctorId();
    }

    protected function getUserId(): int
    {
        self::bootKernel();
        /**  */
        $repo = static::getContainer()->get(UserRepository::class);
        return $repo->getFirstUserId();
    }

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var IDoctorService $service */
        $service       = static::getContainer()->get(\App\Doctor\Interfaces\IDoctorService::class);
        $this->service = $service;
    }

    public function testGetAll(): void
    {

        $result = $this->service->getAllDoctors(null);

        $this->assertIsArray($result);

        foreach ($result as $user) {
            $this->assertInstanceOf(Doctor::class, $user);
        }
    }

    public function testGetDoctorByID(): void
    {
        $id = $this->getDoctorId();

        $result = $this->service->getById($id);

        $this->assertInstanceOf(OutputDoctorDTO::class, $result);
    }

    public function testGetDoctorByUnexistingId(): void
    {
        $id = 0;
        $this->expectException(DoctorNotFoundException::class);
        $this->service->getById($id);
    }

    public function testUpdate(): void
    {
        $id            = $this->getDoctorId();
        $newDoctorData = new UpdateDoctorDTO(
            id: $id
        );
        $result        = $this->service->update($newDoctorData);
        $this->assertInstanceOf(OutputDoctorDTO::class, $result);
    }

    public function testUpdateUnexistingDoctor(): void
    {
        $id            = 0;
        $newDoctorData = new UpdateDoctorDTO(
            id: $id
        );
        $this->expectException(DoctorNotFoundException::class);
        $this->service->update($newDoctorData);
    }

    public function testDeleteDoctorById(): void
    {
        $id     = $this->getDoctorId();
        $result = $this->service->delete($id);
        $this->assertEmpty($result);
    }

    public function testDeleteDoctorByUnexistingId(): void
    {
        $id     = $this->getDoctorId();
        $result = $this->service->delete($id);
        $this->assertEmpty($result);
    }
}
