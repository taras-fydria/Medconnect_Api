<?php

namespace App\User\Exception;

use App\Shared\Exception\DomainException;

class UserAlreadyExistException extends DomainException
{
    public function __construct(string $phone)
    {
        $message = sprintf('User with phone %s already exists.', $phone);
        parent::__construct($message);
    }
}
