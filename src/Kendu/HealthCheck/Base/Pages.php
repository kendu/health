<?php namespace Kendu\HealthCheck\Base;

class Pages implements CheckInterface
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function run()
    {
        $baseUrl = 'http://'.$_SERVER['SERVER_NAME']; 
        $errors = [];

        if (isset($this->params['pages'])) {
            foreach ($this->params['pages'] as $key => $page) {
                $url = $baseUrl . $page;

                $handle = curl_init($url);
                curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($handle, CURLOPT_NOBODY, true);
                
                /* Get the HTML or whatever is linked in $url. */
                $response = curl_exec($handle);

                $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

                if (200 > $httpCode || $httpCode > 300) {
                    $errors[] = sprintf('%s not reachable. Code: "%s"', $page, $httpCode);
                }

                curl_close($handle);
            }

            if (!empty($errors)) {
                return new \Kendu\HealthCheck\Status\Fail([
                    'message' => implode(';', $errors)
                ]);
            }

            return new \Kendu\HealthCheck\Status\Pass;
        }

        return new \Kendu\HealthCheck\Status\Fail([
            'message' => 'No page url defined'
        ]);
    }

    public function id()
    {
        return 'pages';
    }
}