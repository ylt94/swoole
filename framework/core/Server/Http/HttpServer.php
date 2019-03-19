<?php


namespace Core\Server\Http;

use Core\Init;
use Swoole\Http\Server;
use Core\Reload;


class HttpServer {

    protected $server;
    public $server_config;
    public $md5_file;


    public function __construct(){
       $this->server_config = Init::$config['swoole_http'];
    }

    public function run(){
        $this->server=new Server($this->server_config['server']['host'],$this->server_config['server']['port']);

        $this->server->set($this->server_config['server']['config']);
        $this->server->on('request',[$this,'request']);
        $this->server->on('Start',[$this,'start']);
        $this->server->on('workerStart',[$this,'workerStart']);


        $this->server->start();
    }


    public function HotReload(){
        $reload = new Reload();
        swoole_timer_tick(3000,function ($reload){

            if(!$reload->FilesCheck()){
                $this->server->reload();
            };
           

        });
    }

    public  function  request($request,$response){
        $uri = $request->server['request_uri'];

        if ($uri == '/favicon.ico') {
            $response->status(404);
            $response->end();
        }else{
            $this->reload();
            $response->end('peter');
        }
    }

    public  function  start($server){

           $this->HotReload();

    }
    
    public  function  workerStart($server,$worker_id){


    }
}