<?php

namespace Kendu\HealthCheck\Base;

use Kendu\HealthCheck\CheckInterface;
use Kendu\HealthCheck\Status\Fail;
use Kendu\HealthCheck\Status\Pass;
use SoapClient;

/**
 * Class SoapCheck checks SOAP connection.
 *
 * Example:
 *      new Kendu\HealthCheck\Base\SoapCheck('webservicex', 'http://www.webservicex.net/geoipservice.asmx?WSDL')
 */
class SoapCheck implements CheckInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $wsdl;

    /**
     * @var array
     */
    private $streamContext;

    /**
     * @var array
     */
    private $options;

    /**
     * @var SoapClient
     */
    protected $SoapClient;

    /**
     * @param $key
     * @param $wsdl
     * @param array $options
     */
    public function __construct($key, $wsdl, array $options = [])
    {
        $this->key = $key;
        $this->wsdl = $wsdl;
        $this->options = $options;
        $this->streamContext = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'allow_self_signed' => true
            ]
        ]);
    }

    public function run()
    {
        try {
            //set connection timeout
            ini_set('default_socket_timeout', 2);

            $this->SoapClient = new SoapClient($this->wsdl, [
                    "trace"          => 1,
                    "exceptions"     => true,
                    'stream_context' => $this->streamContext,
                ] + $this->options
            );
        } catch (\Exception $e) {
            return new Fail(
                ['message' => sprintf('Error: %s', $e->getMessage())]
            );
        }

        return new Pass;
    }

    public function id()
    {
        return 'soap_' . $this->key;
    }
}
