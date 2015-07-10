<?php namespace Kendu\HealthCheck;

interface CheckInterface
{
    /**
     * Run the check.
     */
    public function run();

    /**
     * Get check id.
     */
    public function id();
}
