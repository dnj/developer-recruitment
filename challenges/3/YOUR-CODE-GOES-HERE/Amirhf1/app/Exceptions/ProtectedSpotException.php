<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class ProtectedSpotException extends Exception
{
	use JsonRender;
}
