<?php


require_once __DIR__ . '/vendor/autoload.php';
$config = require_once __DIR__ . '/config/index.php';
$config['root_path'] = __DIR__;
new Core\Init($config);

(new Core\Server\Http\HttpServer())->run();