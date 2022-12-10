<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class CarDoesNotArrivedAtOriginException extends Exception
{
	use JsonRender;

	public function __construct(string $message = "Car does not arrived at origin spot, so passenger could not got in the car.", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
