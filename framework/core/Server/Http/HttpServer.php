<?php


namespace Core\Server\Http;

use Core\Init;
use Swoole\Http\Server;
use Core\Reload;
use Core\RequestHandle\HttpHandle;
use Core\Server\Rpc\RpcServer;

class HttpServer {

    protected $server;
    public $server_config;
    public $md5_file;


    public function __construct(){
       $this->server_config = Init::$config['http']['server'];
    }

    public function run(){
        $this->server=new Server($this->server_config['host'],$this->server_config['port']);

        $this->server->set($this->server_config['setting']);
        $this->server->on('request',[$this,'request']);
        $this->server->on('Start',[$this,'start']);
        $this->server->on('workerStart',[$this,'workerStart']);

        //启动rpc
        if(isset($this->server_config['enable_tcp']) && $this->server_config['enable_tcp']){
            RpcServer::listen($this->server,Init::$config['tcp']['server']);
        }
        $this->server->start();
    }
    

    public function HotReload(){
        $reload = new Reload();
        $server = $this->server;
        swoole_timer_tick(3000,function () use ($server,$reload){

            if(!$reload->FilesCheck()){
                $server->reload();
            };
           

        });
    }

    public  function  request($request,$response){
        echo 'http request'.PHP_EOL;
        //HttpHandle::dispatch($request,$response);
    }

    public  function  start($server){
        echo 'http server start'.PHP_EOL;
        //$this->HotReload();

    }
    
    public  function  workerStart($server,$worker_id){
        echo 'http worker start'.PHP_EOL;
    }

    public function shutdown(\swoole_server $server){
        echo 'http server stop'.PHP_EOL;
    }
}