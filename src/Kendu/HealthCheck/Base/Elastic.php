<?php namespace Kendu\HealthCheck\Base;

use Kendu\HealthCheck\Status;

class Elastic implements \Kendu\HealthCheck\CheckInterface
{
    public function __construct(array $params)
    {
        $this->params = $params + [
            'scheme' => 'http',
            'host' => getenv('ELASTIC_HOST'),
            'port' => 9200
        ];
    }

    public function run()
    {
        $ch = curl_init(sprintf(
            '%s://%s:%d/%s/_stats',
            $this->params['scheme'],
            $this->params['host'],
            $this->params['port'],
            $this->params['index']
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if ($response === null || isset($response['error'])) {
            return new Status\Fail(
                ['message' => $response['error']['type'] ?? 'invalid response']
            );
        }

        return new Status\Pass();
    }

    public function id()
    {
        return 'elastic';
    }
}
