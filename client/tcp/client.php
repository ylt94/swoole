<?php


//实例化对象
$client = new Swoole\Client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);

//设置
$client->set([
    'open_length_check'=>1,
    'package_length_type'=>'N',//设置包头字节序
    'package_length_offset'=>0, //包长度从哪里开始计算
    'package_body_offset'=>4,  //包体从第几个字节开始计算
    'package_max_length'=>1024 * 1024 * 3,
]);

//连接服务端
$client->connect('127.0.0.1',9800);

//发送数据
$data = [
    'msg' => '客户端发来数据',
];
$data = json_encode($data);
$send_data = pack('N',strlen($data)).$data;

$client->send($send_data);

//接受服务端返回信息
/**
 * size 缓冲池大小
 * 是否等待所有数据到达后返回
 */
$res = $client->recv(1024 * 1024 * 2,1);
if(!$res){
    echo '接受数据失败：'.PHP_EOL;
    exit;
}

echo '数据接收成功'.PHP_EOL;

// $close_res = $client->close();
// if(!$close_res){
//     echo '连接关闭失败';
//     exit;
// }
//  echo '连接关闭成功';