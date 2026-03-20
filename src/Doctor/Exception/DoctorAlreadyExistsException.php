<?php

namespace App\Doctor\Exception;

use App\Shared\Exception\DomainException;

class DoctorAlreadyExistsException extends DomainException
{
    public function __construct(int $userId)
    {
        parent::__construct(sprintf('Doctor for user %d already exists', $userId));
    }
}