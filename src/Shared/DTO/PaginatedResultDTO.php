<?php

namespace App\Shared\DTO;

class PaginatedResultDTO
{
    public function __construct(
        public array $items,
        public int   $total,
        public int   $limit,
        public int   $offset,
    )
    {
    }
}
