<?php
// src/MessageHandler/ProcessStepMessageHandler.php

namespace App\MessageHandler;

use App\Message\ProcessStepMessage;
use App\ModuleProcess\Orchestrator\ProcessOrchestrator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcessStepMessageHandler
{
    public function __construct(
        private ProcessOrchestrator $orchestrator
    ) {
    }
    
    public function __invoke(ProcessStepMessage $message): void
    {
        $this->orchestrator->handleStep(
            $message->processId,
            $message->step
        );
    }
}
