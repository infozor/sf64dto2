<?php

namespace App\ModuleProcess\Orchestrator;

use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\ProcessStepMessage;

final class ProcessOrchestrator
{
	public function __construct(private Connection $db, private MessageBusInterface $bus)
	{
	}
	public function startProcess(int $processId, string $processType, int $inputValue): void
	{
		$this->db->beginTransaction();

		try
		{
			// Создаем процесс
			$this->db->executeStatement('
                INSERT INTO process_instance
                (process_type, state, current_step, started_at)
                VALUES (:type, :state, :step, now())
            ', [
					'type' => $processType,
					'state' => 'pending',
					'step' => 'calculate'
			]);

			$processId = $this->db->lastInsertId();

			// Сохраняем входные данные
			$this->db->executeStatement('
                INSERT INTO process_payload
                (process_id, input_value)
                VALUES (:id, :value)
            ', [
					'id' => $processId,
					'value' => $inputValue
			]);

			$this->db->commit();

			// Запускаем первый шаг
			$this->bus->dispatch(new ProcessStepMessage($processId, 'calculate'));
		}
		catch ( \Throwable $e )
		{
			$this->db->rollBack();
			throw $e;
		}
	}
	public function handleStep(int $processId, string $step): void
	{
		$this->db->beginTransaction();

		try
		{
			$process = $this->db->fetchAssociative('SELECT * FROM process_instance WHERE id = :id FOR UPDATE', [
					'id' => $processId
			]);

			if (!$process || $process['state'] === 'done')
			{
				$this->db->commit();
				return;
			}

			$this->db->executeStatement('
                UPDATE process_instance
                SET state = :state, current_step = :step, updated_at = now()
                WHERE id = :id
                ', [
					'state' => 'running',
					'step' => $step,
					'id' => $processId
			]);

			match ($step) {
					'calculate' => $this->calculate($processId),
					'apply' => $this->apply($processId),
					default => throw new \LogicException("Unknown step {$step}")
			};

			$this->db->commit();
		}
		catch ( \Throwable $e )
		{
			$this->db->rollBack();

			$this->db->executeStatement('
                UPDATE process_instance
                SET state = :state, error_message = :err
                WHERE id = :id
                ', [
					'state' => 'failed',
					'err' => $e->getMessage(),
					'id' => $processId
			]);

			throw $e;
		}
	}
	private function calculate(int $processId): void
	{
		$payload = $this->db->fetchAssociative('SELECT input_value FROM process_payload WHERE process_id = :id', [
				'id' => $processId
		]);

		if (!$payload)
		{
			throw new \RuntimeException('Payload not found');
		}

		$result = $payload['input_value'] * 2;

		$this->db->executeStatement('
            INSERT INTO process_result (process_id, result_value)
            VALUES (:id, :value)
            ON CONFLICT (process_id)
            DO UPDATE SET result_value = EXCLUDED.result_value
            ', [
				'id' => $processId,
				'value' => $result
		]);

		$this->bus->dispatch(new ProcessStepMessage($processId, 'apply'));
	}
	private function apply(int $processId): void
	{
		$result = $this->db->fetchAssociative('SELECT result_value FROM process_result WHERE process_id = :id', [
				'id' => $processId
		]);

		if (!$result)
		{
			throw new \RuntimeException('Result not found');
		}

		// Здесь могла быть реальная бизнес-логика
		// Например: обновление заказа, баланса, статуса

		$this->db->executeStatement('
            UPDATE process_instance
            SET state = :state, finished_at = now(), updated_at = now()
            WHERE id = :id
            ', [
				'state' => 'done',
				'id' => $processId
		]);
	}
}