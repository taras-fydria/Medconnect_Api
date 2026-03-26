<?php

namespace App\Doctor\Entity;

use App\Doctor\Interfaces\IDoctorRepository;
use App\Doctor\Specialization;
use App\User\UserEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Column(enumType: Specialization::class)]
    private Specialization $specialization;

    #[ORM\Column(length: 255, unique: true)]
    private string $licenseNumber;

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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getSpecialization(): Specialization
    {
        return $this->specialization;
    }

    public function setSpecialization(string $specialization): self
    {
        $this->specialization = Specialization::from($specialization);
        return $this;
    }

    public function getLicenseNumber(): string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): self
    {
        $this->licenseNumber = $licenseNumber;
        return $this;
    }
}
