<?php namespace Kendu\HealthCheck\Base;

class Space implements \Kendu\HealthCheck\CheckInterface
{
    const ONE_GB = 1073741824; // 2^30;

    public function __construct(array $params)
    {
        $this->params = $params + [
            // 1GB as default minimum
            'min' => self::ONE_GB,

            // Check current directory by default...
            'dir' => __DIR__,
        ];
    }

    public function run()
    {
        $space = disk_free_space($this->params['dir']);

        if ($space >= $this->params['min']) {
            return new \Kendu\HealthCheck\Status\Pass(["message" => "ok"]);
        }

        return new \Kendu\HealthCheck\Status\Fail(
            ['message' => sprintf(
                'Insufficient disk space: %.1f GB.',
                $space / self::ONE_GB
            )]
        );
    }

    public function id()
    {
        return 'space';
    }
}
