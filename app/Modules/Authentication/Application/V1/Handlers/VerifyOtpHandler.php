<?php
namespace App\Modules\Authentication\Application\V1\Handlers;

use App\Core\Contracts\Cache\CacheServiceInterface;
use App\Modules\Authentication\Application\Commands\VerifyOtpCommand;
use App\Core\Contracts\Security\OtpServiceInterface;
use App\Modules\Authentication\Domain\Exceptions\InvalidOtpException;
use App\Modules\Authentication\Application\Services\UserService;
use App\Modules\Authentication\Application\V1\Commands\VerifyOtpCommand as CommandsVerifyOtpCommand;
use App\Modules\Authentication\Domain\Enums\UserStatus;
use App\Modules\Authentication\Domain\Exceptions\OtpExpiredException;
use App\Modules\Authentication\Domain\Repositories\UserRepositoryInterface;
use App\Modules\User\Domain\Exceptions\InvalidStatusException;
use App\Modules\User\Domain\Exceptions\UserNotFoundException as ExceptionsUserNotFoundException;

final class VerifyOtpHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly OtpServiceInterface $otpService,
        private readonly CacheServiceInterface $cacheService,
        // private readonly EventDispatcherInterface $events
    ) {}
    

    public function handle(CommandsVerifyOtpCommand $command): array
    {
        
        
        $userEntity = $this->userRepository->findById($command->userId);

        if (!$userEntity) {
            throw new ExceptionsUserNotFoundException("User ID {$command->userId} not found.");
        }

        // 2. Validate the OTP
        $isValid = false;

        try {
            $isValid = $this->otpService->validate(
                key: "user:{$userEntity->getId()}",
                code: $command->otp
            );
        } catch (OtpExpiredException $e) {
            throw $e; // safe to bubble up
        }

        if (!$isValid) {
            throw new InvalidOtpException();
        }

        // 4. Fire event
        // $this->events->dispatch(new UserOtpVerified(
        //     userId: $user->id
        // ));

        // 5. Return result
        return [
            'status'  => 'verified',
            'message' => 'OTP successfully validated.',
            'user_id' => $userEntity->getId(),
        ];


    }

}
