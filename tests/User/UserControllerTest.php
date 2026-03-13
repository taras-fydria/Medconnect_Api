<?php

namespace App\Tests\User;

use App\User\UserEntity;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public const PHONE = '+79991234567';
    public const PASSWORD = 'secret123L@';
    public const NEW_PHONE = '+79991234568';
    public const NEW_PASSWORD = 'secret123L@!';
    private const WRONG_PHONE = '+79991234569';
    private const WRONG_PASSWORD = 'secret123L!';

    protected function getExistingUser(): UserEntity
    {
        $userRepository = static::getContainer()->get(\App\User\UserRepository::class);
        return $userRepository->findByPhone(\App\DataFixtures\UserFixture::PHONE);
    }

    protected function getJwtManager(): JWTTokenManagerInterface
    {
        return static::getContainer()->get(JWTTokenManagerInterface::class);
    }

    protected function getUserToken(): string
    {
        $user = $this->getExistingUser();
        return $this->getJwtManager()->create($user);
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $token  = $this->getUserToken();

        $server = [
            'HTTP_Authorization' => 'Bearer ' . $token,
            'CONTENT_TYPE'       => 'application/json',
        ];

        $client->request('GET', '/api/user', server: $server);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());
    }


    public function testCreateUser()
    {
        $data    = ['phone' => self::NEW_PHONE, 'password' => self::NEW_PASSWORD];
        $content = json_encode($data);
        $client  = static::createClient();
        $client->request('POST', '/api/user/register', content: $content);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('phone', $responseData);
    }

    public function testRegistrationWithExistingPhone(): void
    {
        $client = static::createClient();

        $payload = json_encode([
            'phone'    => self::PHONE,
            'password' => self::PASSWORD,
        ]);

        $client->request('POST', '/api/user/register', [], [],
            ['CONTENT_TYPE' => 'application/json'], $payload
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testLoginUser(): void
    {
        $content = json_encode(['phone' => self::PHONE, 'password' => self::PASSWORD]);
        $client  = static::createClient();

        $client->request('POST', '/api/user/login', server: ['CONTENT_TYPE' => 'application/json'], content: $content);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertIsString($responseData['token']);
    }

    public function testLoginUserWithWrongNumber(): void
    {
        $wrong_content = json_encode(['phone' => self::WRONG_PHONE, 'password' => self::NEW_PASSWORD]);
        $server        = ['CONTENT_TYPE' => 'application/json'];
        $client        = static::createClient();

        $client->request('POST', '/api/user/login', server: $server, content: $wrong_content);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame('401');
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testLoginUserWithWrongPassword(): void
    {
        $wrong_content = json_encode(['phone' => self::NEW_PHONE, 'password' => self::WRONG_PASSWORD]);
        $server        = ['CONTENT_TYPE' => 'application/json'];
        $client        = static::createClient();

        $client->request('POST', '/api/user/login', server: $server, content: $wrong_content);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testUpdateUser(): void
    {
        $client  = static::createClient();
        $server  = [
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $this->getUserToken(),
        ];
        $id = $this->getExistingUser()->getId();
        $content = json_encode(['id' => $this->getExistingUser()->getId(), 'phone' => self::NEW_PHONE, 'password' => self::NEW_PASSWORD]);

        $client->request('PUT', "/api/user/{$id}", server: $server, content: $content);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $id = $this->getExistingUser()->getId();
        $server = [
            'HTTP_Authorization' => 'Bearer ' . $this->getUserToken(),
        ];
        $client->request('DELETE', "/api/user/{$id}", server: $server);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
