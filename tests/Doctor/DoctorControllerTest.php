<?php

namespace App\Tests\Doctor;

use App\Doctor\Entity\Doctor;
use App\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DoctorControllerTest extends WebTestCase
{
    protected function getUserToken(): string
    {
        $user = static::getContainer()->get(UserRepository::class)->findOneBy([]);
        return static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);
    }

    protected function authHeaders(): array
    {
        return ['HTTP_Authorization' => 'Bearer ' . $this->getUserToken()];
    }

    public function testUnauthorizedIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/doctor');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testIndexReturnsListWhenAuthenticated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/doctor', server: $this->authHeaders());

        $content = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($content);
        $this->assertIsArray(json_decode($content, true));
    }

    public function testShowUser(): void
    {
        $client = static::createClient();

        $user   = static::getContainer()->get(UserRepository::class)->findOneBy([]);
        $em     = static::getContainer()->get(EntityManagerInterface::class);
        $doctor = new Doctor();
        $doctor->setUser($user);
        $em->persist($doctor);
        $em->flush();

        $client->request('GET', "/api/doctor/{$doctor->getId()}", server: $this->authHeaders());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testShowNotFoundUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/doctor/0', server: $this->authHeaders());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreateDoctor(): void
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testCreateDoctorUnauthorized(): void
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testCreateDoctorDuplicate(): void
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testUpdateDoctor(): void
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testUpdateDoctorNotFound(): void
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testDeleteDoctor(): void
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testDeleteDoctorNotFound(): void
    {
        $this->markTestIncomplete('Not implemented');
    }
}
