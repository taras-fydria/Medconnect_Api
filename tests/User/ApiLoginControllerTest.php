<?php

namespace App\Tests\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiLoginControllerTest extends WebTestCase
{
    private const PHONE = '+79991234567';
    private const PASSWORD = 'secret123L@';

    public function testCreateUser()
    {
        $data    = ['phone' => self::PHONE, 'password' => self::PASSWORD];
        $content = json_encode($data);
        $client  = static::createClient();
        $client->request('POST', '/api/register', content: $content);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('phone', $responseData);
        $this->assertArrayHasKey('password', $responseData);
    }

    public function testLoginUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/login', ['phone' => self::PHONE, 'password' => self::PASSWORD]);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertArrayHasKey('id', $responseData);
        $this->assertIsString($responseData['token']);
    }
}
