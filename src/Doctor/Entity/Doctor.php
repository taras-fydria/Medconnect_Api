<?php

namespace App\Doctor\Entity;

use App\Doctor\Interfaces\IDoctorRepository;
use App\User\UserEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IDoctorRepository::class)]
#[ORM\Table(name: "doctor")]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToOne(targetEntity: UserEntity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "cascade")]
    private UserEntity $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }

    public function setUser(UserEntity $user): self
    {
        $this->user = $user;
        return $this;
    }
}
