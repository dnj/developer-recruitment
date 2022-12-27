<?php
if ( !function_exists('getUser') ) {
	function getUser () {
		return auth('sanctum')->user();
	}
}