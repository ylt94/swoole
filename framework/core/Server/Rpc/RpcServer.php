<?php

namespace Core\Server\Rpc;

use Core\Init;

class RpcServer{


    public static function listen($server,$tcp){
        $listen=$server->listen($tcp['host'], $tcp['port'],SWOOLE_SOCK_TCP);
        $listen->set($tcp['swoole_setting']);
        $listen->on("receive",[self,'receive']);
    }
    public static  function  receive(){
          //route->dispatch(); //分发到指定的服务当中
          //var_dump('222');
    }
}