<?php

namespace App\Shared\DTO;

class PaginationDTO
{
    public function __construct(
        public int $limit = 100,
        public int $offset = 0,
    )
    {
    }

    public function __serialize(): array
    {
        // TODO: Implement __serialize() method.
    }
}
