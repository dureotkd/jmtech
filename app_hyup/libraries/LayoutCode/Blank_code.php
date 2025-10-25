<?php

class blank_code
{

	public function __construct()
	{

		$this->obj = &get_instance();

		$this->obj->load->model('template_code_model');
	}

	public function getUseData($data = null) {}
}
