<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class CannotCancelFinishedTravelException extends Exception
{
	use JsonRender;

	public function __construct(string $message = "This travel is done, you cannot cancel it.", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
