<?php

namespace App\Modules\Authentication\Application\V1\Commands;

final class VerifyOtpCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $otp,
    ) {}
}
