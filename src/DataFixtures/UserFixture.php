<?php

namespace App\DataFixtures;

use App\User\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public const PHONE    = '+79991234567';
    public const PASSWORD = 'secret123L@';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $known = new UserEntity();
        $known->setPhone(self::PHONE);
        $known->setPassword($this->hasher->hashPassword($known, self::PASSWORD));
        $manager->persist($known);

        $faker = Factory::create();
        for ($i = 0; $i < 5; $i++) {
            $user = new UserEntity();
            $user->setPhone('+1' . $faker->numerify('##########'));
            $user->setPassword($this->hasher->hashPassword($user, $faker->password(8, 16)));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
