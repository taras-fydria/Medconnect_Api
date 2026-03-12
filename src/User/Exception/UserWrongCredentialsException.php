<?php

namespace App\User\Exception;

class UserWrongCredentialsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Wrong Password or Phone Number');
    }
}
