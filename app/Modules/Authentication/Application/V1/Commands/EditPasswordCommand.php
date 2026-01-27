<?php

namespace App\Modules\Authentication\Application\V1\Commands;

final class EditPasswordCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $password,
        public readonly ?string $oldPassword
    ) {}
}
