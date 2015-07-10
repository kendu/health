<?php namespace Kendu\HealthCheck\Auth;

interface AuthInterface
{
	/**
	 * Test credentials
	 */
	public function auth();
}