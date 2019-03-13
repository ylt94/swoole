<?php



//实例化
$server = new Swoole\Server('0.0.0.0','9800',SWOOLE_PROCESS,SWOOLE_SOCK_TCP);

//设置
$server->set(
    [
        'reactor_num' => 1,
        'worker_num' => 2,
        'task_work_num' => 2,
        'task_ip_mode' => 2,
        'message_queue_key' => ftok(__DIR__,1),
        'heartbeat_check_interval' => 3,//心跳检测间隔时间
        'heartbeat_idle_time' => 7,//tcp允许最大闲置时间
        'open_length_check'=>1,
        'package_length_type'=>'N',//设置包头字节序
        'package_length_offset'=>0, //包长度从哪里开始计算
        'package_body_offset'=>4,  //包体从第几个字节开始计算
        'package_max_length'=>1024 * 1024 * 3,
        'buffer_output_size'=>1024 * 1024 * 3, //输出缓冲区的大小
        //'daemonize' => 1 //守护进程化
    ]
);

//注册新连接监听事件
$server->on('connect',function(swoole_server $server,int $fd){
    echo '有新的连接进来：'.$fd.PHP_EOL;
});

//注册消息接受监听事件
$server->on('receive',function(swoole_server $server,int $fd,int $reactor_id, string $data){
    $send_data = $data;
    $data = substr($data,4);
    $data = json_decode($data,true);
    // echo $data['msg'].PHP_EOL;
    // echo '开始向客户端发送应答'.PHP_EOL;
    // $server->send($fd,$send_data);

    // //广播
    // echo '开始广播udp'.PHP_EOL;
    // $udp_client = new Swoole\Client(SWOOLE_SOCK_UDP);
    // $udp_client->sendto('127.0.0.1',9801,'我是客户端');
    // echo '广播发送完毕'.PHP_EOL;
    //$res = $udp_client->recv(1024 * 1024 * 2,1);
    //echo '接收到新消息，长度：'.strlen($data).PHP_EOL;
    $server->task($data,2);
});

$server->on('task',function(swoole_server $server, int $task_id, int $src_worker_id, mixed $data){
    echo 'task进程接收到任务,task_id:'.$task_id.'src_worker_id:'.$src_worker_id.PHP_EOL;
    echo $data['msg'];
    $server->finish('task任务执行完成');
});

$server->on('finish',function(swoole_server $server, int $task_id, string $data){
    echo 'task任务执行返回结果:'.$data.PHP_EOL;
});

//注册连接关闭监听事件
$server->on('close',function(swoole_server $server,int $fd){
    echo '连接已关闭'.PHP_EOL;
});

//启动服务
$ser_star_res = $server->start();
if(!$ser_star_res){
    echo '服务启动失败'.PHP_EOL;
}