<?php


// src/Message/TestJobMessageM.php
namespace App\Message;

class TestJobMessageM
{
	/**
	 * @param int $jobId
	 * @param array<string, mixed> $payload
	 */
	public function __construct(
			public readonly int $jobId,
			public readonly array $payload = []
			)
	{
				$this->validate();
	}
	
	private function validate(): void
	{
		if ($this->jobId <= 0) {
			throw new \InvalidArgumentException('JobId must be a positive integer.');
		}
	}
	
	/**
	 * Получить значение из payload по ключу
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, mixed $default = null): mixed
	{
		return $this->payload[$key] ?? $default;
	}
	
	/**
	 * Преобразование в массив (удобно для логов/дебага)
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return [
				'jobId' => $this->jobId,
				'payload' => $this->payload,
		];
	}
}
