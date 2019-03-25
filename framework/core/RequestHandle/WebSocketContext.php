<?php

namespace Core\RequestHandle;


class WebSocketContext{
    /*
     *  fd=>[
     *     $fd,
     *     $path,
     *     $request
     * ]
     * */

    private  static  $connecitons=[];

    //初始化
    public  static  function  init($fd,$path){
         self::$connecitons[$fd]['path']=$path;
    }
    //获取连接所对应的path信息
    public  static  function  get($fd=null){
            if($fd==null){
                return null;
            }
            return self::$connecitons[$fd]??null;
    }
    //删除方法
    public  static  function  del($fd=null){
        if($fd==null){
            return false;
        }
        if(isset(self::$connecitons[$fd])){
            unset(self::$connecitons[$fd]);
            return true;
        }
        return false;
    }
}