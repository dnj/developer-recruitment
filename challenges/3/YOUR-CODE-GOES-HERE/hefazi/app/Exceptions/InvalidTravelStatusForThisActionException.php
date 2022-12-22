<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class InvalidTravelStatusForThisActionException extends Exception
{
	use JsonRender;
}
