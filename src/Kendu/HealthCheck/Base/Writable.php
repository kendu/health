<?php namespace Kendu\HealthCheck\Base;

class Writable implements \Kendu\HealthCheck\CheckInterface
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function run()
    {
        $errors = [];

        foreach ($this->params['paths'] as $path) {
            if (!is_writable($path)) {
                $errors[] = sprintf('Path "%s" not writable', $path);
            }
        }

        if ($errors) {
            return new \Kendu\HealthCheck\Status\Fail([
                'message' => implode('; ', $errors)
            ]);
        }

        return new \Kendu\HealthCheck\Status\Pass;
    }

    public function id()
    {
        return 'writable';
    }
}
