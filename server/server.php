<?php



//实例化
$server = new Swoole\Server('0.0.0.0','9800',SWOOLE_PROCESS,SWOOLE_SOCK_TCP);

//设置
$server->set(
    [
        'reactor_num' => 1,
        'worker_num' => 2,
        'heartbeat_check_interval' => 30,//心跳检测间隔时间
        'heartbeat_idle_time' => 70,//tcp允许最大闲置时间
    ]
);