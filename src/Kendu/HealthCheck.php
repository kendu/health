<?php

namespace Kendu;

use HealthCheck\Response;
use Exception;

class HealthCheck
{
    protected $checks;
    protected $authenticated = false;

    /**
     * Create object and set an array of object to execute.
     *
     * @param array $checks Checks to execute
     */
    public function __construct(array $checks)
    {
        foreach ($checks as $check) {
            if (!$check instanceof 'Kendu\HealthCheck\CheckInterface') {
                throw new Exception(
                    sprintf('Check %s must implement Kendu\HealthCheck\CheckInterface.', get_class($check))
                );
            }
        }

        $this->classes = $classes;
    }

    /**
     * Authenticate against a token.
     *
     * @param string $token Token
     */
    public function authenticate($token)
    {
        // @todo: implement
        $this->authenticated = true;
    }

    public function verifyToken()
    {
        if (!$authenticated) {
            return new Response('Not authenticated.', 500);
        }
    }

    /**
     * Execute the checks and return a status reponse.
     *
     * @return Kendu\HealthCheck\Response
     */
    public function check()
    {
        $this->verifyToken();

        $status = [];
        $code = 200;

        foreach ($this->checks as $check) {
            $response = $check->run();

            if (!$response->ok()) {
                $code = 500;
            }

            $status[$check->id()] = $response;
        }

        return new Response($status, $code);
    }
}
