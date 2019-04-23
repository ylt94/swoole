<?php

use Swoole\Process;
use Swoole\Process\Pool;

$process1 = new Process(function(swoole_process $process1){
    echo '进程1'.PHP_EOL;
    $pool = new Pool(1);
    $pool->on('WorkerStart',function($pool,$workerId){
        //echo var_dump($pool);
        echo 'workerid:'.$workerId.PHP_EOL;
        sleep(20);
    });
    $pool->start();
});
$process2 = new Process(function(swoole_process $process2){
    echo '进程2'.PHP_EOL;
});
$process3 = new Process(function(swoole_process $process3){
    echo '进程3'.PHP_EOL;
});

$process1->start();
$process2->start();
$process3->start();
Process::wait();
