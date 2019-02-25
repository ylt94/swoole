<?php


$client = new Swoole\Client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);

$client->connect('127.0.0.1',9800,1,0);

//异步客户端必须注册所有事件

//连接事件回调
$client->on("connect", function(swoole_client $cli){
    echo '已连接上服务端'.PHP_EOL;
});

//异步回调客户端
$client->on("receive", function(swoole_client $cli, $data){
      echo '已获取到服务端返回数据：'.$data;
     //$cli->send(str_repeat('A', 100)."\n");

});

$client->on("error", function(swoole_client $cli){
    echo "error\n";
});


$client->on("close", function(swoole_client $cli){
      echo "链接已关闭\n";
});

$client->connect('127.0.0.1', 9800) || exit("");

//定时器,保持长连接
swoole_timer_tick(3000,function () use($client){
    //发送数据
    $data = [
        'msg' => '客户端发来数据',
    ];
    $data = json_encode($data);
    $send_data = pack('N',strlen($data)).$data;
    echo '开始发送数据'.PHP_EOL;
    $client->send($send_data);
});
