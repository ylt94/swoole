<?php

$cli = new swoole_http_client('127.0.0.1', 9500);
$cli->setHeaders([
    'Host' => $domainName,
    'UserAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
    'Accept' => 'text/html,application/xhtml+xml,application/xml',
    'Accept-Encoding' => 'gzip',
]);
$cli->upgrade('/', function ($cli) {
    $cli->push("hello world");
});