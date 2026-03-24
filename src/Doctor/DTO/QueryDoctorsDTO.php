<?php

namespace App\Doctor\DTO;

use App\Shared\DTO\PaginationDTO;
use App\Shared\DTO\SortDTO;
use App\Shared\DTO\SortEnum;

class QueryDoctorsDTO
{
    public function __construct(
        public PaginationDTO $pagination = new PaginationDTO(),
        public ?SortDTO      $sort = null,
        public ?array        $returnFields = null
    )
    {
    }
}
