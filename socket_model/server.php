<?php



class worker{

    private $on_methods = [
        'connect' => null,
        'receive' => null
    ];
    private $err_msg = false;
    private $server = null;
    public $sockets = [];
    
    public function __construct($socket_address){
        $this->server = stream_socket_server($socket_address);
        stream_set_blocking($this->server,0);
        $this->sockets[(int)$this->server] = $this->server;
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
        $this->accept();
    }


    //循环监听
    public function accept(){

        if($this->err_msg){
            echo $this->err_msg;exit;
        }

        
        while(true){
            $write = $except = [];
            $read = $this->sockets;
            stream_select($read,$write,$except,60);
            foreach($read as $key => $val){
                if($val === $this->server){//服务端改变,说明服务端可读，有新的连接进来
                    $client = stream_socket_accept($this->server);
                    
                    //调用回调
                    if($client && $this->on_methods['connect']){
                        $this->callBackAction($this->on_methods['connect'],$client);
                    }
                    $this->sockets[(int)$client] = $client;
                }else{//客户端变化，说明是客户端可读，有数据发送到服务端
                    //处理信息
                    $request = fread($val,65535);
                    if(!$request && (feof($val) || !is_resource($val))){
                        fclose($val);
                        unset($this->sockets[(int)$val]);
                        continue;
                    }

                    //调用回调事件
                    $this->callBackAction($this->on_methods['receive'],$val,$request);
                }
            }
            
            usleep(500);
        }
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

