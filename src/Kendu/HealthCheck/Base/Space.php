<?php namespace Kendu\HealthCheck\Base;

class Space implements \Kendu\HealthCheck\CheckInterface
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function run()
    {
        $space = disk_free_space($this->params['dir']);

        // require the minimum, if set; otherwise, at least 1 GB
        $min = isset($this->params['min']) ? $this->params['min'] : 1000000000;

        if ($space >= $min) {
            return new \Kendu\HealthCheck\Status\Pass;
        }

        return new \Kendu\HealthCheck\Status\Fail(
            ['message' => sprintf('Insufficient disk space: %f MB.', $space / 1024)]
        );
    }

    public function id()
    {
        return 'space';
    }
}
