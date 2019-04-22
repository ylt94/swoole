<?php

use Swoole\Process\Pool;
use Swoole\Process;

Process::daemon();
$pool = new Pool(10);

$pool->on('WorkerStart',function($pool,$workerId){
    echo var_dump($pool);
    echo 'workerid:'.$workerId.PHP_EOL;
    sleep(20);
});

$pool->start();