<?php

namespace App\User\DTO;

class UserRequestDTO
{
    public function __construct(
        public ?int $offset,
        public ?int $limit
    )
    {
    }
}
