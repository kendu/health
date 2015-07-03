<?php

namespace Kendu\HealthCheck\Status;

class Error implements StatusInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        if (isset($this->data['message'])) {
            return $this->data['message'];
        }

        // @todo: compose a string
        return null;
    }
}
