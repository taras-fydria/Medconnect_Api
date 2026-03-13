<?php

namespace App\User;

use App\Shared\Exception\ValidationException;
use App\User\DTO\RegisterUserDTO;
use App\User\Exception\UserAlreadyExistException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly ValidatorInterface          $validator,
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


    public function getAll(): array
    {
        return $this->userRepository->findBy([], limit: 100, offset: 0);
    }

    public function getOne(int $id): UserEntity
    {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            throw new NotFoundHttpException(sprintf('User with id %d not found.', $id));
        }
        return $user;
    }

    public function update(int $id, RegisterUserDTO $dto): UserEntity
    {
        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        $user     = $this->getOne($id);
        $existing = $this->userRepository->findByPhone($dto->phone);

        if ($existing !== null && $existing->getId() !== $user->getId()) {
            throw new UserAlreadyExistException($dto->phone);
        }

        $hashedPassword = $this->hasher->hashPassword($user, $dto->password);

        $user
            ->setPhone($dto->phone)
            ->setPassword($hashedPassword);

        $this->userRepository->saveOne($user);
        return $user;
    }

    public function delete(int $id): void
    {
        $this->userRepository->deleteOne($this->getOne($id));
    }

}
