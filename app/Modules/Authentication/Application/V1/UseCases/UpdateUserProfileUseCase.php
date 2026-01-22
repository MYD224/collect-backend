<?php

namespace App\Modules\Authentication\Application\V1\UseCases;

use App\Core\Contracts\Cache\CacheServiceInterface;
use App\Modules\Authentication\Application\Exceptions\CannotUpdateUserException;
use App\Modules\Authentication\Application\Services\HashingService;
use App\Modules\Authentication\Application\V1\Commands\UpdateUserProfileCommand;
use App\Modules\Authentication\Application\V1\Data\UserData;
use App\Modules\Authentication\Application\V1\Handlers\UpdateUserProfileHandler;
use App\Modules\Authentication\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Authentication\Domain\ValueObjects\Id;
use App\Modules\Authentication\Infrastructure\Persistence\Eloquent\Models\User;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use Illuminate\Support\Facades\Log;
use Carbon\CarbonImmutable;

class UpdateUserProfileUseCase
{
    public function __construct(
        // private UpdateUserProfileHandler $handler,
        private readonly UserRepositoryInterface $userRepository,
        private readonly HashingService $hashingService,
        private readonly CacheServiceInterface $cache,
    ) {}

    public function execute(UpdateUserProfileCommand $command)
    {
        try {
            $userEntity = $this->cache->remember(
                key: "user:" . $command->id . ":session",
                ttl: 3600,
                callback: fn() => $this->userRepository->findById($command->id)
            );
            // $userEntity = $this->userRepository->findById($userEntity->getId());
            if (!$userEntity) throw new UserNotFoundException();
            // return UserData::fromEntity($userEntity);

            if (!$userEntity->getPhoneVerifiedAt()) {

                // Préparer les nouvelles valeurs
                $phoneVerifiedAt = CarbonImmutable::parse($command->phoneVerifiedAt);

                $status = $userEntity->getStatus();

                // Mettre à jour l'utilisateur
                $userEntity->update(
                    // status: $status,
                    phoneVerifiedAt: $phoneVerifiedAt,
                );

                // $updatedUserEntity = $userEntity;
                // Persister les modifications
                $updatedUserEntity = $this->userRepository->save($userEntity);
                $this->cache->delete("user:" . $command->id . ":session");
                $this->cache->set(
                    key: "user:" . $command->id . ":session",
                    ttl: 3600,
                    value: $updatedUserEntity
                );
            } else {
                $updatedUserEntity = $userEntity;
            }


            // 5. Emit event
            // event(new UserUpdatedEvent($user));


            return UserData::fromEntity($updatedUserEntity);
        } catch (\Throwable $th) {
            // Log the error
            // Log::error("Failed to update user: {$th->getMessage()}", [
            //     'user_id' => (new Id($command->id))->value(),
            // ]);

            // Transform infrastructure errors → domain-safe exception
            throw new CannotUpdateUserException($th->getMessage());
        }
    }


    // public function execute(string $userId)
    // {
    //     return $this->cache->remember("users.$userId", 300, function () use ($userId) {
    //         $user = $this->repo->findById($userId);

    //         if (!$user) {
    //             throw new UserNotFoundException();
    //         }

    //         return $user;
    //     });
    // }


    // Redis version

    // public function execute(string $id)
    // {
    //     return $this->cache->rememberTag(
    //         tag: "users",
    //         key: "user:$id",
    //         ttl: 600,
    //         callback: function () use ($id) {
    //             $user = $this->repo->findById($id);
    //             if (!$user) throw new UserNotFoundException();
    //             return $user;
    //         }
    //     );
    // }
}
