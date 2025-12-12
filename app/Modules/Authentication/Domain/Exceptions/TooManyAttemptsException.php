<?php

namespace App\Modules\Authentication\Domain\Exceptions;

use DomainException as GlobalDomainException;

final class OtpExpiredException extends GlobalDomainException
{
    protected $message = "You have exceeded the maximum number of authorized attempts.";
}
