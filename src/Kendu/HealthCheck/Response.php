<?php namespace Kendu\HealthCheck;

class Response
{
    /**
     * Construct and send the reponse.
     *
     * @param array $data       Data to send
     * @param int   $code     HTTP response code
     */
    public function __construct(array $data, $code)
    {
        $this->data = $data;

        http_response_code($code);

        $this->send();
    }

    /**
     * Set a header.
     */
    public function setHeader($key, $value)
    {
        header($key . ': ' . $value);
    }

    /**
     * Send JSON response.
     */
    public function send()
    {
        $this->setHeader('Content-Type', 'application/json');

        echo json_encode(array_map('strval', $this->data), JSON_PRETTY_PRINT);
        exit;
    }
}
