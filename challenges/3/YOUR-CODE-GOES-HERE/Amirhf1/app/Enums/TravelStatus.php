<?php
namespace App\Enums;

enum TravelStatus : string {
	case SEARCHING_FOR_DRIVER = "SEARCHING_FOR_DRIVER";
	case RUNNING = "RUNNING";
	case DONE = "DONE";
	case CANCELLED = "CANCELLED";
}
