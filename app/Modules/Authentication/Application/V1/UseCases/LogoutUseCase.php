<?php
namespace App\Modules\Authentication\Application\V1\UseCases;

use App\Modules\Authentication\Application\V1\Commands\LogoutCommand;
use App\Modules\Authentication\Application\V1\Handlers\LogoutHandler;

class LogoutUseCase
{
     public function __construct(
        private LogoutHandler $handler
    ) {}

    public function execute(LogoutCommand $command): array
    {
        return $this->handler->handle($command);
    }
}
