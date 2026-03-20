<?php

namespace App\DataFixtures;

use App\Doctor\Entity\Doctor;
use App\User\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DoctorFixture extends Fixture implements DependentFixtureInterface
{
    public const PHONE    = '+79990000001';
    public const PASSWORD = 'secret123L@';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function getDependencies(): array
    {
        return [UserFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        // Known doctor
        $knownUser = new UserEntity();
        $knownUser->setPhone(self::PHONE);
        $knownUser->setPassword($this->hasher->hashPassword($knownUser, self::PASSWORD));
        $manager->persist($knownUser);

        $knownDoctor = new Doctor();
        $knownDoctor->setUser($knownUser);
        $manager->persist($knownDoctor);

        // Random doctors
        $faker = Factory::create();
        for ($i = 0; $i < 1000; $i++) {
            $user = new UserEntity();
            $user->setPhone('+2' . $faker->numerify('##########'));
            $user->setPassword($this->hasher->hashPassword($user, $faker->password(8, 16)));
            $manager->persist($user);

            $doctor = new Doctor();
            $doctor->setUser($user);
            $manager->persist($doctor);
        }

        $manager->flush();
    }
}
