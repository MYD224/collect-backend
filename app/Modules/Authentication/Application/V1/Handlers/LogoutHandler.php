<?php
namespace App\Modules\Authentication\Application\V1\Handlers;


use App\Modules\Authentication\Application\Commands\VerifyOtpCommand;
use App\Core\Contracts\Security\OtpServiceInterface;
use App\Modules\Authentication\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use App\Modules\Authentication\Domain\Exceptions\InvalidOtpException;
use App\Modules\Authentication\Domain\Events\UserOtpVerified;
use App\Core\Contracts\EventDispatcherInterface;
use App\Modules\Authentication\Application\Services\UserService;
use App\Modules\Authentication\Application\V1\Commands\LogoutCommand;
use App\Modules\Authentication\Application\V1\Commands\VerifyOtpCommand as CommandsVerifyOtpCommand;
use App\Modules\Authentication\Domain\Enums\UserStatus;
use App\Modules\Authentication\Domain\Exceptions\OtpExpiredException;
use App\Modules\User\Domain\Exceptions\UserNotFoundException as ExceptionsUserNotFoundException;

final class LogoutHandler
{
    public function __construct(
        private readonly UserService $userService,
        private readonly OtpServiceInterface $otpService,
        // private readonly EventDispatcherInterface $events
    ) {}

    public function handle(LogoutCommand $command)
    {
        // 1. Load user from domain repo
       
    }
}
