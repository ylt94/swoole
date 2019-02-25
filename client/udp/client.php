<?php



$client = new Swoole\Client(SWOOLE_SOCK_UDP);

$client->sendto('127.0.0.1',9801,'我是客户端');


$res = $client->recv(1024 * 1024 * 2,1);
if(!$res){
    echo '接受数据失败'.PHP_EOL;
}