<?php
require_once ROOT_PATH . '/vendor/autoload.php';

class RedirectTool
{
    static function redirectTo(string $url, array $params)
    {
        $queryString = http_build_query($params);
        $headerLocation = 'Location: ' . $url . '?' . $queryString;
        header($headerLocation);
    }
}
