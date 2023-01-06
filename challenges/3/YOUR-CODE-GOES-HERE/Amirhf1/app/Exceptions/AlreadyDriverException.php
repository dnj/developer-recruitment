<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class AlreadyDriverException extends Exception
{
	use JsonRender;

	public function __construct(string $message = "You already are driver", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
