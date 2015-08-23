<?php

namespace Kendu\HealthCheck\Base;

use Kendu\HealthCheck\CheckInterface;
use Kendu\HealthCheck\Status\Fail;
use Kendu\HealthCheck\Status\Pass;
use MongoClient;

/**
 * Class MongoDB checks Mongo database connection.
 *
 * Example:
 *      new Kendu\HealthCheck\Base\MongoDbCheck('localhost', 'database_name')

 */
class MongoDbCheck implements CheckInterface
{

    /**
     * @var string
     */
    private $server;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var array
     */
    private $options;

    /**
     * @param $server
     * @param $dbname
     * @param array $options
     */
    public function __construct($server, $dbname, array $options = [])
    {
        $this->server = $server;
        $this->dbname = $dbname;
        $this->options = $options;
    }

    public function run()
    {
        if (!extension_loaded("mongo")) {
            return new Fail(
                [
                    'message' => sprintf(
                        'Mongo extension is not loaded in PHP(check with "php -m" or "phpinfo()").')
                ]
            );
        }

        try {
            $connection = new MongoClient($this->server, $this->options);
            $connection->selectDB($this->dbname);

        } catch (\Exception $e) {
            return new Fail(
                ['message' => sprintf('Error: %s', $e->getMessage())]
            );
        }

        return new Pass;
    }

    public function id()
    {
        return 'mongoDB';
    }
}
