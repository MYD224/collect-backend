<?php

namespace App\Modules\Authentication\Domain\Exceptions;

use DomainException;

class InvalidPasswordException extends DomainException
{
    public function __construct($message = 'Invalid password provided.')
    {
        parent::__construct($message);
    }
}
