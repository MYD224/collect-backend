<?php

namespace App\Modules\Authentication\Application\V1\UseCases;

use App\Core\Contracts\Cache\CacheServiceInterface;
use App\Modules\Authentication\Application\V1\Commands\AssignRoleToUserCommand;
use App\Modules\Authentication\Domain\Entities\UserEntity;
use App\Modules\Authentication\Domain\Repositories\UserRepositoryInterface;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Log;


class AssignRolesToUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CacheServiceInterface $cacheService,
    ) {}

    public function execute(AssignRoleToUserCommand $command)
    {
        $user = $this->cacheService->remember(
            key: "user:" . $command->userId . ":session",
            ttl: 3600,
            callback: fn() => $this->userRepository->findById($command->userId)
        );
        if (!$user) {
            throw new UserNotFoundException("User not found!");
        }

        $this->userRepository->assignRolesToUser($command->userId, $command->roles);


        // return $this->cacheService->remember(
        //     key: "user:" . $userEntity->getId() . ":session",
        //     ttl: 3600,
        //     callback: fn() => $this->userRepository->save($userEntity)
        // );
    }
}
