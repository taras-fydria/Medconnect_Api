<?php

namespace App\User;

use App\Shared\Exception\ValidationException;
use App\User\DTO\RegisterUserDTO;
use App\User\Exception\UserAlreadyExistException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(
        private UserRepository              $userRepository,
        private UserPasswordHasherInterface $hasher,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    public function register(RegisterUserDTO $dto): UserEntity
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        $existing = $this->userRepository->findByPhone($dto->phone);

        if ($existing !== null) {
            throw new UserAlreadyExistException($dto->phone);
        }

        $user = new UserEntity()->setPhone($dto->phone);

        $hashedPassword = $this->hasher->hashPassword($user, $dto->password);

        $user->setPassword($hashedPassword);

        $this->userRepository->saveOne($user);

        return $user;
    }
}
