<?php

namespace App\Doctor\DTO;

use App\User\UserEntity;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDoctorDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public UserEntity $user,
    )
    {
    }

}
