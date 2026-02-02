<?php

namespace App\Modules\Authentication\Infrastructure\Services;

class AuthService
{
    public function attemptLogin(string $phone, string $password): bool
    {
        return auth()->attempt(['phone' => $phone, 'password' => $password]);
    }
}
