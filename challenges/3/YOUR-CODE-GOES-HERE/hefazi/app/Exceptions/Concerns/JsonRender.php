<?php
namespace App\Exceptions\Concerns;

trait JsonRender {
	public function render()
	{
		return response(array(
			'code' => str_replace("Exception", "", class_basename(get_class($this))),
			'message' => $this->message
		), 400);
	}
}