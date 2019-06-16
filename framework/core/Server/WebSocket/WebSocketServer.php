<?php

namespace Core\Server\WebSocket;

use Core\Init;
use Core\Server\Http\HttpServer;
use Swoole\WebSocket\Server;
use Core\RequestHandle\WebSocketHandle;
use Core\RequestHandle\WebSocketContext;

class WebSocketServer extends HttpServer{

    public $server_config;

    public function __construct(){
        $this->server_config = Init::$config['web_socket']['server'];
    }

    public  function  run(){

        $this->server=new Server($this->server_config['host'],$this->server_config['port']);
        $this->server->set($this->server_config['setting']);

        $this->server->on('Start',[$this,'start']);
        $this->server->on('workerStart',[$this,'workerStart']);

        $this->server->on('open',[$this,'open']);
        $this->server->on('message',[$this,'message']);
        $this->server->on('close',[$this,'close']);

        //启动http服务
        if (isset($this->server_config['enable_http']) && $this->server_config['enable_http']) {
            $this->server->on('request',[$this,'request']);
        }
        //启动rpc
        if(isset($this->server_config['enable_tcp']) && $this->server_config['enable_tcp']){
            RpcServer::listen($this->server,Init::$config['tcp']['server']);
        }

        $this->server->start();

          //1.热重启

          //2.能够支持http请求,解析路由

          //3.能够拥有websocket控制器 ()
    }

    public  function open($server,$request){
            // $path=$request->server['path_info'];
            // $fd=$request->fd;
            // WebSocketContext::init($fd,$path); //保存了上下文信息
            // //触发到控制器,路由
            // WebSocketHandle::open($server,$request,$path);
            echo 'ws open'.PHP_EOL;

    }

    public  function message($server,$frame){
        //var_dump($request->server['path_info']);
        $fd=$frame->fd;
        echo 'ws message'.PHP_EOL;
        //$path=WebSocketContext::get($fd);
        //var_dump($path);
    }

    public  function close($server,$fd){
       // var_dump($request->server['path_info']);
        $path=WebSocketContext::get($fd);
        echo 'ws close'.PHP_EOL;
        var_dump($path);

    }

}