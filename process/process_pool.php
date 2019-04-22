<?php

use Swoole\Process\Pool;
use Swoole\Process;


$pool = new Pool(1);

$pool->on('WorkerStart',function($pool,$workerId){
    //echo var_dump($pool);
    //echo 'workerid:'.$workerId.PHP_EOL;
    sleep(20);
});

$pool->on("WorkerStop", function ($pool, $workerId) {
    echo "Worker#{$workerId} is stopped\n".PHP_EOL;
});


$pool->start();
swoole_set_process_name('process_pool');