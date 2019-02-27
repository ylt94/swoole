<?php



class worker{

    private $on_methods = [
        'connect' => null,
        'receive' => null
    ];
    private $err_msg = false;
    private $socket = null;
    
    public function __construct($socket_address){
        $this->socket = stream_socket_server($socket_address);
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

    public function start(){

        if($this->err_msg){
            echo $this->err_msg;exit;
        }

        while(true){

            //阻塞监听进来的连接
            $accept = stream_socket_accept($this->socket);
            if($accept && $this->on_methods['connect']){
                $this->callBackAction($this->on_methods['connect'],$accept);
            }
        
            //处理信息
            $content = fread($accept,65535);
            if($content && $this->on_methods['receive']){
                $this->callBackAction($this->on_methods['receive'],$content);
            }
            usleep(500);
        }
    }

    
    //检查回调事件是否注册
    private function checkMethod(string $method){
        return array_key_exists($method,$this->on_methods);
    }

    private function callBackAction(Closure $method,$param){
        call_user_func($method,$param);
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

$worker->on('receive',function($content){
    echo '成功获取请求参数:'.$content.PHP_EOL;
});


$worker->start();

