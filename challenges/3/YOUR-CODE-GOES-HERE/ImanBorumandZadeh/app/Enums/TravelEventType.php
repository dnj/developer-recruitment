<?php
namespace App\Enums;

enum TravelEventType : string {
	case ACCEPT_BY_DRIVER = "ACCEPT_BY_DRIVER";
	case PASSENGER_ONBOARD = "PASSENGER_ONBOARD";
	case DONE = "DONE";
	case CANCEL = "CANCEL";
}