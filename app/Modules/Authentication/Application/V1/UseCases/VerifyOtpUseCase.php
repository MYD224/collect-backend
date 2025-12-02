<?php
namespace App\Modules\Authentication\Application\V1\UseCases;

use App\Modules\Authentication\Application\V1\Commands\RegisterUserCommand;
use App\Modules\Authentication\Application\V1\Commands\VerifyOtpCommand;
use App\Modules\Authentication\Application\V1\Handlers\VerifyOtpHandler;

class VerifyOtpUseCase
{
     public function __construct(
        private VerifyOtpHandler $handler
    ) {}

    public function execute(VerifyOtpCommand $command): array
    {
        return $this->handler->handle($command);
    }
}
