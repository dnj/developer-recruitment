<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class CannotCancelRunningTravelException extends Exception
{
	use JsonRender;
}
