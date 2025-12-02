<?php
namespace App\Modules\Authentication\Application\V1\UseCases;

use App\Modules\Authentication\Application\V1\Commands\GenerateOtpCommand;
use App\Modules\Authentication\Application\V1\Commands\VerifyOtpCommand;
use App\Modules\Authentication\Application\V1\Handlers\GenerateOtpHandler;


class GenerateOtpUseCase
{
     public function __construct(
        private GenerateOtpHandler $handler
    ) {}

    public function execute(GenerateOtpCommand $command): array
    {
        return $this->handler->handle($command);
    }
}
