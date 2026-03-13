<?php

namespace App\Doctor\DTO;


use App\User\UserEntity;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDoctorDTO extends CreateDoctorDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public int $id,
        UserEntity $user,
    )
    {
        parent::__construct(user: $user);
    }
}
