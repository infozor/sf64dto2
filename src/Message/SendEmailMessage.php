<?php

// src/Message/SendEmailMessage.php
namespace App\Message;

class SendEmailMessage
{
	public function __construct(public string $email)
	{
		$a = 1;
	}
}
