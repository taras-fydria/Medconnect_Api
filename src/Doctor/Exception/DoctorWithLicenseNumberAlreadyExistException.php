<?php

namespace App\Doctor\Exception;

use App\Shared\Exception\DomainException;

class DoctorWithLicenseNumberAlreadyExistException extends DomainException
{
    public function __construct(string $licenseNumber)
    {
        parent::__construct(sprintf('Doctor with license number already exists: %s', $licenseNumber));
    }
}