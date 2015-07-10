<?php namespace Kendu\HealthCheck\Status;

abstract class Prototype
{
    protected $data;

    public function __construct($data)
    {
    	if (is_scalar($data)) {
    		$this->data = ['messsage' => $data];
    	} else {
        	$this->data = $data;
    	}

    }

    public function __toString()
    {
        if (array_key_exists('message', $this->data)) {
            return $this->data['message'];
        }

        // @todo: compose a string
        return '';
    }
}
