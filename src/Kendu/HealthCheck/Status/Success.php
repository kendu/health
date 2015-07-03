<?php

namespace Kendu\HealthCheck\Status;

class Success implements StatusInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        if (array_key_exists('message', $this->data)) {
            return $this->data['message'];
        }

        // @todo: compose a string
        return null;
    }
}
