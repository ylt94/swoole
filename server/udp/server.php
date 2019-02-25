<?php

//实例化对象
$server = new Swoole\Server('0.0.0.0',9801,SWOOLE_PROCESS,SWOOLE_SOCK_UDP);

//设置
$server->set(
    [
        'reactor_num' => 1,
        'worker_num' => 2,
        'heartbeat_check_interval' => 30,//心跳检测间隔时间 对于udp无效
        'heartbeat_idle_time' => 70,//tcp允许最大闲置时间 对于udp无效
    ]
);

//监听事件
/**
 * data 接收到的数据包
 * clientInfo 发送数据包的客户端信息
 * 
 */
$server->on('packet',function (swoole_server $server,string $data,array $clientInfo){
    //var_dump($data,$clientInfo);
    echo '接收到数据'.$data.PHP_EOL;
    echo '开始应答'.PHP_EOL;
    $server->sendto($clientInfo['address'],$clientInfo['port'],'服务端数据包');
});

//服务器开启
$server->start();