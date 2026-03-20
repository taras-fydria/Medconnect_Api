<?php

namespace App\Doctor\Exception;

use App\Shared\Exception\DomainException;

class DoctorNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct(sprintf('Doctor not found: %d', $id));
    }
}
