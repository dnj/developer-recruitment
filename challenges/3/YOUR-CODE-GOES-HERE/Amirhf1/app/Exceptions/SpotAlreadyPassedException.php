<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class SpotAlreadyPassedException extends Exception
{
	use JsonRender;

	public function __construct(string $message = "You already passed this spot.", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
