<?php

namespace App\Modules\Authentication\Domain\Exceptions;

use App\Core\Exceptions\DomainException;
use DomainException as GlobalDomainException;

final class InvalidOtpException extends GlobalDomainException
{
    protected $message = "The OTP code is incorrect.";
}
