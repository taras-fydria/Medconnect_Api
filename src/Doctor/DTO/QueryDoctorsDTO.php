<?php

namespace App\Doctor\DTO;

class QueryDoctorsDTO
{
    public function __construct(
        public int $limit = 100,
        public int $offset = 0,
    )
    {
    }
}
