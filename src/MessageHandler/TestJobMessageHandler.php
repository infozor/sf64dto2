<?php

// src/MessageHandler/TestJobMessageHandler.php
namespace App\MessageHandler;

use App\Message\TestJobMessageM;
use App\ModuleProcess\Orchestrator\ProcessOrchestrator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TestJobMessageHandler
{
	/*
	 public function __invoke(TestJobMessage $message): void
	 {
	 dump('JOB EXECUTED', $message->jobId);
	 }
	 */
	public function __construct(private ProcessOrchestrator $orchestrator)
	{
	}
	/*
	public function __invoke(TestJobMessageM $message): void
	{
		// Основные данные
		$jobId = $message->jobId;

		// Дополнительные данные из payload
		$email = $message->get('email');
		$attempt = $message->get('attempt', 1);

		dump('JOB EXECUTED', [
				'jobId' => $jobId,
				'email' => $email,
				'attempt' => $attempt
		]);

		// Здесь дальше — бизнес-логика
	}
	*/
	
	public function __invoke(TestJobMessageM $message): void
	{
		//$jobId = $message->jobId;
		
		$this->orchestrator->handleStep(
				$message->processId,
				$message->step
				);
		/*
		$this->orchestrator->handleStep(
				$message->processId,
				$message->payload
				);
		*/		
	}
}
