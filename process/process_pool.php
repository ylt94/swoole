<?php

use Swoole\Process\Pool;
use Swoole\Process;


$pool1 = new Pool(1);
$pool2 = new Pool(2);

$pool1->on('WorkerStart',function($pool,$workerId){
    //echo var_dump($pool);
    //echo 'workerid:'.$workerId.PHP_EOL;
    sleep(20);
});

$pool1->on("WorkerStop", function ($pool, $workerId) {
    echo "Worker#{$workerId} is stopped\n".PHP_EOL;
});

$pool2->on('WorkerStart',function($pool,$workerId){
    //echo var_dump($pool);
    echo 'workerid:'.$workerId.PHP_EOL;
    sleep(20);
});

$pool2->on("WorkerStop", function ($pool, $workerId) {
    echo "Worker#{$workerId} is stopped\n".PHP_EOL;
});


$pool1->start();
$pool2->start();
