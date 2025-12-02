<?php

namespace App\Modules\Authentication\Application\V1\Commands;

final class LogoutCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $token,
    ) {}
}
