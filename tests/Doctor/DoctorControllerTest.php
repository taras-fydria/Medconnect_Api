<?php

namespace App\Tests\Doctor;

use App\DataFixtures\DoctorFixture;
use App\Doctor\Entity\Doctor;
use App\Doctor\Specialization;
use App\User\UserEntity;
use App\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DoctorControllerTest extends WebTestCase
{
    private ?string $authToken = null;

    private function authHeaders(): array
    {
        if ($this->authToken === null) {
            $this->authToken = static::getContainer()
                ->get(JWTTokenManagerInterface::class)
                ->create($this->getUser());
        }

        return ['HTTP_Authorization' => 'Bearer ' . $this->authToken];
    }

    private function getUserRepository(): UserRepository
    {
        return static::getContainer()->get(UserRepository::class);
    }

    private function getUser(): UserEntity
    {
        return $this->getUserRepository()->findOneBy([]);
    }

    private function getUserWithoutDoctor(): UserEntity
    {
        $em = static::getContainer()->get(EntityManagerInterface::class);

        return $em->createQuery(
            'SELECT u FROM App\User\UserEntity u WHERE u.id NOT IN (SELECT IDENTITY(d.user) FROM App\Doctor\Entity\Doctor d)'
        )->setMaxResults(1)->getSingleResult();
    }

    private function getKnownDoctor(): Doctor
    {
        $em   = static::getContainer()->get(EntityManagerInterface::class);
        $user = $this->getUserRepository()->findByPhone(DoctorFixture::PHONE);

        return $em->createQuery(
            'SELECT d FROM App\Doctor\Entity\Doctor d WHERE d.user = :userId'
        )->setParameter('userId', $user->getId())->getSingleResult();
    }

    private function createDoctor(): Doctor
    {
        $user   = $this->getUserWithoutDoctor();
        $doctor = new Doctor();
        $doctor->setUser($user);
        $doctor->setFirstName('Ivan');
        $doctor->setLastName('Petrov');
        $doctor->setSpecialization(Specialization::Cardiology->value);
        $doctor->setLicenseNumber('LIC-TEST');

        return $doctor;
    }

    private function saveDoctor(Doctor $doctor): void
    {
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $em->persist($doctor);
        $em->flush();
    }

    private function getNewDoctorData(int $userID): array
    {
        return [
            'firstName'      => 'Ivan',
            'lastName'       => 'Petrov',
            'licenseNumber'  => 'LIC-TEST',
            'specialization' => Specialization::Cardiology->value,
            'userID'         => $userID,
        ];
    }

    public function testUnauthorizedIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/doctor');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testIndexReturnsListWhenAuthenticated(): void
    {
        $client  = static::createClient();
        $client->request('GET', '/api/doctor', server: $this->authHeaders());
        $content = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($content);
        $this->assertIsArray(json_decode($content, true));
    }

    public function testShowDoctor(): void
    {
        $client   = static::createClient();
        $doctorId = $this->getKnownDoctor()->getId();

        $client->request('GET', "/api/doctor/{$doctorId}", server: $this->authHeaders());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testShowDoctorNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/doctor/0', server: $this->authHeaders());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreateDoctor(): void
    {
        $client  = static::createClient();
        $user    = $this->getUserWithoutDoctor();
        $content = json_encode($this->getNewDoctorData($user->getId()));

        $client->request('POST', '/api/doctor', server: $this->authHeaders(), content: $content);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testCreateDoctorUnauthorized(): void
    {
        $client  = static::createClient();
        $user    = $this->getUser();
        $content = json_encode($this->getNewDoctorData($user->getId()));

        $client->request('POST', '/api/doctor', content: $content);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateDoctorWithAttachedUserID(): void
    {
        $client   = static::createClient();
        $doctorId = $this->getKnownDoctor()->getUser()->getId();
        $content  = json_encode($this->getNewDoctorData($doctorId));

        $client->request('POST', '/api/doctor', server: $this->authHeaders(), content: $content);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateDoctor(): void
    {
        $client   = static::createClient();
        $doctor   = $this->createDoctor();
        $this->saveDoctor($doctor);
        $doctorId = $doctor->getId();
        $content  = json_encode($this->getNewDoctorData($doctor->getUser()->getId()));

        $client->request('PUT', "/api/doctor/{$doctorId}", server: $this->authHeaders(), content: $content);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testUpdateDoctorNotFound(): void
    {
        $client  = static::createClient();
        $userID = $this->getUser()->getId();
        $content = json_encode($this->getNewDoctorData($userID));
        $doctorId = 1000000;
        $client->request('PUT', "/api/doctor/$doctorId", server: $this->authHeaders(), content: $content);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteDoctor(): void
    {
        $client   = static::createClient();
        $doctor   = $this->createDoctor();
        $this->saveDoctor($doctor);
        $doctorId = $doctor->getId();
        var_dump($doctorId);
        $client->request('DELETE', "/api/doctor/{$doctorId}", server: $this->authHeaders());

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteDoctorNotFound(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/doctor/0', server: $this->authHeaders());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
