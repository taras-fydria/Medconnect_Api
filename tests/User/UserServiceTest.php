<?php

namespace App\Tests\User;

use App\User\DTO\RegisterUserDTO;
use App\User\UserEntity;
use App\User\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserServiceTest extends KernelTestCase
{
    const string VALID_PHONE = '+380991234567';
    const string VALID_PASSWORD = '12345678L@';
    private UserService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->service = static::getContainer()->get(\App\User\UserService::class);
    }

    public function testCreateUser(): void
    {
        $dto    = new RegisterUserDTO(self::VALID_PHONE, self::VALID_PASSWORD);
        $result = $this->service->register($dto);
        $this->assertInstanceOf(UserEntity::class, $result);
        $this->assertEquals(self::VALID_PHONE, $result->getPhone());
        $this->assertObjectHasProperty('password', $result);
        $this->assertNotEmpty($result->getPassword());
        $this->assertNotEmpty($result->getPhone());
        $this->assertNotEquals(self::VALID_PASSWORD, $result->getPassword());
    }

    public function testCreateUserWithExistingPhone(): void
    {
        $dto  = new RegisterUserDTO(self::VALID_PHONE, self::VALID_PASSWORD);
        $user = $this->service->register($dto);

        $this->assertInstanceOf(UserEntity::class, $user);
        $this->assertEquals(self::VALID_PHONE, $user->getPhone());
        $this->assertObjectHasProperty('password', $user);
        $this->expectException(\App\User\Exception\UserAlreadyExistException::class);
        $this->service->register($dto);


        $em = static::getContainer()->get('doctrine.orm.entity_manager');
        $count = $em->createQuery('SELECT COUNT(u) FROM App\User\UserEntity u')
            ->getSingleScalarResult();

        $this->assertEquals(1, $count);
    }

    public function testCreateUserWithInvalidPhone(): void
    {
        $dto = new RegisterUserDTO('not-a-phone', self::VALID_PASSWORD);

        $this->expectException(\App\Shared\Exception\ValidationException::class);

        $this->service->register($dto);
    }

    public function testCreateUserWithWeakPassword(): void
    {
        $dto = new RegisterUserDTO(self::VALID_PHONE, '123');

        $this->expectException(\App\Shared\Exception\ValidationException::class);

        $this->service->register($dto);
    }
}
