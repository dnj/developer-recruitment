<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class AllSpotsDidNotPassException extends Exception
{
	use JsonRender;

	public function __construct(string $message = "Some of travel spots does not pass.", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
