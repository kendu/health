<?php namespace Kendu\HealthCheck\Auth;

class SecretHttpGetParameter implements AuthInterface
{
	protected $key    = null;
	protected $secret = null;
	protected $source = [];

	/**
	 * @param string Secret to check
	 * @param string (HTTP GET) Key to search for a secret
	 * @param string Array of HTTP key-value data
	 */
	public function __construct($secret, $key = 'key', array $source = null)
	{
		$this->secret = $secret;
		$this->key    = $key;
		$this->source = is_array($source) ? $source : $_GET;
	}

	public function auth()
	{
		return array_key_exists($this->key, $this->source)
		    && $this->secret == $this->source[$this->key];
	}
}