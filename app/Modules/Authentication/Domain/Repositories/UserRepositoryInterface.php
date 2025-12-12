<?php

namespace App\Modules\Authentication\Domain\Repositories;

use App\Modules\Authentication\Application\V1\Data\UserData;
use App\Modules\Authentication\Domain\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function save(UserEntity $user): UserEntity;
    public function findById(string $id): ?UserEntity;
    public function deleteTokens(string $phone);
    public function generatPassportToken(string $id): string;
    public function findByPhone(string $phone): ?UserEntity;
}