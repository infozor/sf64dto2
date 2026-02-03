<?php

namespace App\Message;


// src/Message/ProcessStepMessage.php
namespace App\Message;

class ProcessStepMessage
{
	public function __construct(public readonly int $processId, public readonly string $step)
	{
	}
}