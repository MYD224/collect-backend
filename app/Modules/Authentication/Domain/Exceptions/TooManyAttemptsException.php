<?php

namespace App\Modules\Authentication\Domain\Exceptions;

use DomainException;

final class TooManyAttemptsException extends DomainException
{
    public function __construct($message = 'You have exceeded the maximum number of authorized attempts.')
    {
        parent::__construct($message);
    }
    //protected $message = "You have exceeded the maximum number of authorized attempts.";
}
