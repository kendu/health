<?php namespace Kendu\HealthCheck\Base;

use Exception;
use Kendu\HealthCheck\Status;

class Postgres implements \Kendu\HealthCheck\CheckInterface
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function run()
    {
        foreach (['dbname', 'user', 'password'] as $param) {
            if (!isset($this->params[$param])) {
                return new \Kendu\HealthCheck\Status\Fail([
                    'message' => sprintf(
                        'health check error: `%s` parameter not set.',
                        $param
                    )
                ]);
            }
        }

        try {
            $connection = pg_connect(
                sprintf(
                    'host=%s port=%s dbname=%s user=%s password=%s',
                    isset($this->params['host']) ? $this->params['host'] : 'db',
                    isset($this->params['port']) ? $this->params['port'] : 5432,
                    $this->params['dbname'],
                    $this->params['user'],
                    $this->params['password']
                )
            );
        } catch (Exception $e) {
            return new Status\Fail([
                'message' => $e->getMessage()
            ]);
        }

        return new Status\Pass;
    }

    public function id()
    {
        return 'postgres';
    }
}
