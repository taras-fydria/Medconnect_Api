<?php

namespace App\Doctor\DTO;

use App\Shared\DTO\PaginationDTO;

class QueryDoctorsDTO
{
    public function __construct(
        public PaginationDTO $pagination = new PaginationDTO(),
    )
    {
    }
}
