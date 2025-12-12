<?php

namespace App\Modules\User\Domain\Exceptions;

use DomainException;

class InvalidStatusException extends DomainException
{
    public function __construct($message = 'This element has not a valid status.')
    {
        parent::__construct($message);
    }
}
