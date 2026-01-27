<?php

namespace App\Modules\Authentication\Application\V1\UseCases;

use App\Core\Contracts\Cache\CacheServiceInterface;
use App\Modules\Authentication\Application\Services\HashingService;
use App\Modules\Authentication\Application\V1\Commands\EditPasswordCommand;
use App\Modules\Authentication\Domain\Exceptions\InvalidPasswordException;
use App\Modules\Authentication\Domain\Repositories\UserRepositoryInterface;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;

class EditPasswordUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly HashingService $hashingService,
        private readonly CacheServiceInterface $cache
    ) {}

    public function execute(EditPasswordCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);
        $this->cache->delete("user:{$command->userId}:session");

        if (!$user) {
            throw new UserNotFoundException("User ID {$command->userId} not found.");
        }
        $hashedPassword = $this->hashingService->hash($command->password);
        if (isset($command->oldPassword) && !$this->hashingService->verify($command->oldPassword, $user->getHashedPassword())) {
            throw new InvalidPasswordException("Current password is incorrect.");
        }

        $this->userRepository->editPassword($command->userId, $hashedPassword);

        // save the newly created password in user passwords table
    }
}
