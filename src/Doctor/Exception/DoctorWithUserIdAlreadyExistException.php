<?php

namespace App\Doctor\Exception;

use Throwable;

class DoctorWithUserIdAlreadyExistException extends \DomainException
{
    public function __construct(int $userID, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Doctor with user ID $userID already exist", $code, $previous);
    }
}
