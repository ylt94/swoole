<?php


$client = Swoole\Client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);

$client->connect('127.0.0.1',9800,1,0);


//发送数据
$data = [
    'msg' => '客户端发来数据',
];
$data = json_encode($data);
$send_data = pack('N',strlen($data)).$data;
echo '开始发送数据'.PHP_EOL;
$client->send($send_data);