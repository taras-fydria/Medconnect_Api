<?php

namespace App\Shared\DTO;

class SortDTO
{
    public function __construct(
        /** @type string[] */
        public array   $sortBy,
        public SortEnum $sortDirection,
    )
    {
    }
}
