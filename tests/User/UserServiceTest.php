<?php

namespace App\Tests\User;

use App\DataFixtures\UserFixture;
use App\Shared\Exception\ValidationException;
use App\User\DTO\RegisterUserDTO;
use App\User\Exception\UserAlreadyExistException;
use App\User\UserEntity;
use App\User\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserServiceTest extends KernelTestCase
{
    const string VALID_PHONE = '+380991234567';
    const string VALID_PASSWORD = '12345678L@';
    const string NEW_PHONE = '+380991234569';
    const string NEW_PASSWORD = '12345678L@!!';
    private UserService $service;
    private int $fixtureUserId;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->service = static::getContainer()->get(\App\User\UserService::class);

        /** @var \App\User\UserRepository $repo */
        $repo = static::getContainer()->get(\App\User\UserRepository::class);
        $this->fixtureUserId = $repo->findByPhone(\App\DataFixtures\UserFixture::PHONE)->getId();
    }

    public function testGetAll(): void
    {
        $result =  $this->service->getAll();

        $this->assertIsArray($result);
        foreach ($result as $user) {
            self::assertInstanceOf(UserEntity::class, $user);
        }
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
    }

    public function testCreateUserWithInvalidPhone(): void
    {
        $dto = new RegisterUserDTO('not-a-phone', self::VALID_PASSWORD);

        $this->expectException(ValidationException::class);

        $this->service->register($dto);
    }

    public function testCreateUserWithWeakPassword(): void
    {
        $dto = new RegisterUserDTO(self::VALID_PHONE, '123');

        $this->expectException(ValidationException::class);

        $this->service->register($dto);
    }


    public function testGetOneUser(): void
    {
        $result = $this->service->getOne($this->fixtureUserId);
        $this->assertInstanceOf(UserEntity::class, $result);
        $this->assertEquals($this->fixtureUserId, $result->getId());
    }

    public function testUpdateUser(): void
    {
        $result = $this->service->update($this->fixtureUserId, new RegisterUserDTO(self::NEW_PHONE, self::NEW_PASSWORD));
        $this->assertInstanceOf(UserEntity::class, $result);
        $this->assertEquals(self::NEW_PHONE, $result->getPhone());
        $this->assertNotEmpty($result->getPassword());
        $this->assertNotEquals(self::NEW_PASSWORD, $result->getPassword());
    }

    public function testUpdateUnexistingUser(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->service->update(0, new RegisterUserDTO(self::NEW_PHONE, self::NEW_PASSWORD));
    }

    public function testUpdateUserWithExistingPhone(): void
    {
        $second = $this->service->register(new RegisterUserDTO(self::VALID_PHONE, self::VALID_PASSWORD));
        $this->expectException(UserAlreadyExistException::class);
        $this->service->update($second->getId(), new RegisterUserDTO(UserFixture::PHONE, self::VALID_PASSWORD));
    }

    public function testDeleteUser(): void
    {
        $result = $this->service->delete($this->fixtureUserId);
        $this->assertTrue($result);
    }

    public function testDeleteUnExistingUser(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->service->delete(0);
    }
}
