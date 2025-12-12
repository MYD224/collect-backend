<?php

namespace App\Core\Application\UseCases;
use App\Modules\Authentication\Domain\Repositories\UserRepositoryInterface;


class GenerateTokenUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(string $userId)
    {
        return $this->userRepository->generatPassportToken($userId);
    }
}
