<?php

namespace App\Modules\Authentication\Application\V1\Commands;

class AssignRoleToUserCommand
{

    public function __construct(
        public string $userId,
        public array $roles
    ) {}
}
