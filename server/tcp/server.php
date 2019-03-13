<?php



//实例化
//$server = new Swoole\Server('0.0.0.0','9800',SWOOLE_PROCESS,SWOOLE_SOCK_TCP);
// $server = new Swoole\Server('0.0.0.0','9800');

// //设置
// $server->set(
//     [
//         //'reactor_num' => 1,
//         'worker_num' => 2,
//         'task_worker_num' => 2,
//         // 'enable_coroutine' => true,
//         // 'task_enable_coroutine' => true,
//         //'task_ip_mode' => 2,
//         //'message_queue_key' => ftok(__DIR__,1),
//         'heartbeat_check_interval' => 3,//心跳检测间隔时间
//         'heartbeat_idle_time' => 7,//tcp允许最大闲置时间
//         'open_length_check'=>1,
//         'package_length_type'=>'N',//设置包头字节序
//         'package_length_offset'=>0, //包长度从哪里开始计算
//         'package_body_offset'=>4,  //包体从第几个字节开始计算
//         'package_max_length'=>1024 * 1024 * 3,
//         'buffer_output_size'=>1024 * 1024 * 3, //输出缓冲区的大小
//         //'daemonize' => 1 //守护进程化
//     ]
// );

// //注册新连接监听事件
// $server->on('connect',function(swoole_server $server,int $fd){
//     echo '有新的连接进来：'.$fd.PHP_EOL;
// });

// //注册消息接受监听事件
// $server->on('receive',function(swoole_server $server,int $fd,int $reactor_id, string $data){
//     // $send_data = $data;
//     // $data = substr($data,4);
//     // $data = json_decode($data,true);
//     // echo $data['msg'].PHP_EOL;
//     // echo '开始向客户端发送应答'.PHP_EOL;
//     // $server->send($fd,$send_data);

//     // //广播
//     // echo '开始广播udp'.PHP_EOL;
//     // $udp_client = new Swoole\Client(SWOOLE_SOCK_UDP);
//     // $udp_client->sendto('127.0.0.1',9801,'我是客户端');
//     // echo '广播发送完毕'.PHP_EOL;
//     //$res = $udp_client->recv(1024 * 1024 * 2,1);
//     //echo '接收到新消息，长度：'.strlen($data).PHP_EOL;

//     //$task_id = rand(0,1);
//     $server->task('11111');//task_id = (0,task_worker_num-1)

//     // $worker_id = rand(0,1);
//     // $server->sendMessage('进程发送给task进程',3);
// });

// $server->on('task',function(swoole_server $server, $task_id,$src_worker_id,$data){
//     echo 'task_id:'.$server->worker_id.PHP_EOL;
//     $server->sendMessage('2222',2);
//     // echo 'task进程接收到任务,task_id:'.$task_id.'src_worker_id:'.$src_worker_id.PHP_EOL;
//     // $task_worker_id = $server->worker_id;
//     // try{
//     //     if($task_worker_id%2){
//     //         throw new Exception('程序执行异常！');
//     //     }
//     // }catch(\Exception $e){
//     //     //只能发送给worker进程
//     //     $worker_id = rand(0,1);
//     //     $server->sendMessage($e->getMessage(),$task_worker_id);//task进程内无法发送消息给task进程
//     // }
//     // echo $data['msg'];
//     // $server->finish('task任务执行完成');
// });

// $server->on('finish',function(swoole_server $server, int $task_id,$data){
//     echo 'task任务执行返回结果:'.$data.PHP_EOL;
// });

// $server->on('PipeMessage',function(swoole_server $server, int $src_worker_id,$message){
//     echo 'task进程执行异常：'.$message.'worker_id:'.$src_worker_id.PHP_EOL;
//     //echo '异常任务重新发送'.PHP_EOL;
//     //$task_id = rand(0,1);
//     //$server->task($data,$task_id);
// });

// //注册连接关闭监听事件
// $server->on('close',function(swoole_server $server,int $fd){
//     echo '连接已关闭'.PHP_EOL;
// });

// //启动服务
// $ser_star_res = $server->start();
// if(!$ser_star_res){
//     echo '服务启动失败'.PHP_EOL;
// }

//创建server对象，监听所有本机9501端口，允许所有IP客户端连接
$server_class = new swoole_server("0.0.0.0",9800);
$server_class->set([
    'reactor_num' => 1,
	"worker_num"=>4,
	"task_worker_num"=>4,
	"open_length_check"=>true,
	"package_length_type"=>"N",
	"package_length_offset"=>0,
	"package_body_offset"=>4,
	"package_max_lenght"=>1024*1024*2,
	"buffer_output_size"=>1024*1024*2,
]);
$server_class->on("connect",function($server_class,$fd){
	//echo "有新的客户端连接，标识符为".$fd.PHP_EOL;
});
$server_class->on("workerStart",function($server_class,$fd){
	if($server_class->taskworker){
		echo "这是task_worker：".$server_class->worker_id.PHP_EOL;
	}else{
		echo "这是worker:".$server_class->worker_id.PHP_EOL;
	}
});
//监听数据接收事件，server接收到客户端的数据后，worker进程内核触发回调
$server_class->on("receive",function($server_class,$fd,$from_id,$data){

    $server_class->task(222);


});
$server_class->on("task",function($server_class,$task_id,$from_id,$data){

      $server_class->sendMessage("123",6);

});
$server_class->on("PipeMessage",function(swoole_server $server_class,int $src_worker_id,$message){

	echo "来自于".$src_worker_id.PHP_EOL;


});
$server_class->on("finish",function($server_class,$task_id,$data){
	echo "任务".$task_id."执行完毕";
});
//监听连接关闭事件，客户端关闭，或者服务器主动关闭
$server_class->on("close",function($server_class,$fd){
	echo "编号为".$fd."的客户端已经关闭".PHP_EOL;
});
//启动服务器
$server_class->start();
?>