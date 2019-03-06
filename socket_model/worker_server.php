<?php



class worker{

    private $on_methods = [
        'connect' => null,
        'receive' => null
    ];
    private $err_msg = false;
    private $server = null;
    private $worker_num = 1;
    private $server_address = null;
    
    public function __construct($socket_address){
        $this->server_address = $socket_address;
    }

    //注册回调事件
    public function on(string $method,Closure $func){

        //检查是否存在改回调事件
        if(!$this->checkMethod($method)){
            $this->setError('注册回调事件不存在');
            return false;
        }
        $this->on_methods[$method] = $func;
    }

    //启动服务端
    public function start(){

        $this->workerFork();
    }

    public function workerFork(){
        for ($i=0;$i<$this->worker_num;$i++){
            $pid=pcntl_fork(); //创建成功会返回子进程id
            if($pid<0){
                exit('创建失败');
            }else if($pid>0){
                //父进程空间，返回子进程id
            }else{ //返回为0子进程空间
                $this->accept();//子进程负责接收客户端请求
                exit;
            }
        }


        //放在父进程空间，结束的子进程信息，阻塞状态
        $status=0;
        for ($i=0;$i<$this->workerNum;$i++) {
            $pid = pcntl_wait($status);
        }
    }

    //循环监听
    public function accept(){
        $opetions = array(
            'socket' => array(
                'backlog' =>10240, //成功建立socket连接的等待个数
            ),
        );
        $context = stream_context_create($opetions);
        //开启多端口监听,并且实现负载均衡(计算机内核自动实现)
        stream_context_set_option($context,'socket','so_reuseport',1);
        stream_context_set_option($context,'socket','so_reuseaddr',1);
        $this->server=stream_socket_server($this->server_address,$errno,$errstr,STREAM_SERVER_BIND|STREAM_SERVER_LISTEN,$context);

        //向内核注册服务端可读事件(即有连接进来)
        swoole_event_add($this->server,function ($fd){

            //服务端可读事件回调，首先创建连接
            $client=stream_socket_accept($fd);
            //调用回调
            if($client && $this->on_methods['connect']){
                $this->callBackAction($this->on_methods['connect'],$client);
            }

            //向内核注册客户端的可读事件(即有消息发送过来)
            swoole_event_add($client,function ($fd){

                //从连接当中读取客户端的内容
                $content=fread($fd,1024);
                //如果数据为空，或者为false,不是资源类型
                if(!$content || !is_resource($content)){
                    fclose($fd);
                }

                //正常读取到数据,触发消息接收事件,响应内容
                $this->callBackAction($this->on_methods['receive'],$fd,$content);
            });
        });
    }

    
    //检查回调事件是否注册
    private function checkMethod(string $method){
        return array_key_exists($method,$this->on_methods);
    }


    //执行回调函数
    private function callBackAction(Closure $method,$client,$content = null){
        call_user_func($method,$client,$content);
    }

    //设置错误信息
    private function setError($msg = false){
        $this->err_msg = $msg;
    }
    
}

$worker = new worker('tcp://127.0.0.1:9802');
$worker->on('connect',function($arg){
    echo '新的连接进来：'.$arg.PHP_EOL;
});

$worker->on('receive',function($client,$content){
    echo '成功获取客户端：'.$client.'请求参数:'.$content.PHP_EOL;
});


$worker->start();

