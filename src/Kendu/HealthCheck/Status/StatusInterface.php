<?php

interface StatusInterface
{
    /**
     * Represent status as a string.
     */
    public function __toString();

    /**
     * Represent status as a boolean.
     */
    public function ok();
}
