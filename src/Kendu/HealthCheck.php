<?php

namespace Kendu;

use Kendu\HealthCheck\Response;
use Kendu\HealthCheck\Auth\AuthInterface;
use Kendu\HealthCheck\CheckInterface;
use Kendu\HealthCheck\Status\Prototype     as Status;
use Kendu\HealthCheck\Status\PassInterface as StatusPassInterface;
use Kendu\HealthCheck\Status\FailInterface as StatusFailInterface;
use Exception;

class HealthCheck
{
    const CHECK_PASS = 200;
    const CHECK_FAIL = 500;

    protected $checks = [];
    protected $auth   = null;

    /**
     * Create object and set an array of object to run.
     *
     * First parameter can be a class that implements AuthInteface.
     *
     * @param  $checks Array of health checks to run
     * @param  $auth   Optional authentication check to access the output
     */
    public function __construct(array $checks, AuthInterface $auth = null)
    {
        // Process all checks
        $this->checks = [];
        array_walk($checks, [$this, 'addCheck']);

        // Add authenticator
        $this->setAuth($auth);
    }

    public function addCheck(CheckInterface $check) {
        $this->checks[] = $check;
    }

    /**
     * Authenticate against a token.
     *
     * @param string $token Token
     */
    public function setAuth(AuthInterface $auth)
    {
        // @todo: implement
        $this->auth = $auth;
    }

    public function authorize()
    {
        if (!$this->auth) {
            // Noone cares
            return true;
        }

        if ($this->auth->auth()) {
            // Looks like valid credentails were
            // passed, carry on...
            return true;
        }

        return new Response(['error' => 'Not authenticated.'], 403);
    }

    /**
     * Execute the checks and return a status reponse.
     *
     * @return Kendu\HealthCheck\Response
     */
    public function run()
    {
        $this->authorize();

        $health = [];
        $code = self::CHECK_PASS;

        foreach ($this->checks as $check) {
            $response = $check->run();

            if ($response instanceof Status) {
                // Divided we stand ...
                $health[$check->id()] = $response;
            }

            if ($response instanceof StatusFailInterface) {
                // ... united we fail!
                $code = self::CHECK_FAIL;
            }
        }

        return new Response($health, $code);
    }
}
