<?php

namespace Core\RequestHandle;

use Core\Route;
use Core\Init;

class WebSocketHandle{
    public static function  open($server,$request,$path){
        try{
            //通过路径得到类名然后实例化
             $className=self::getClassName($path);

             if($className!=null){
                 $obj=new $className;
                 $obj->open($server,$request);
             }


        }catch (\Throwable $t){
            var_dump($t->getMessage());
        }
   }

   public static function  message(){

   }

   public static  function  close(){

   }

   public static function getClassName($path=null){
            if($path==null){
               return null;
            }
            $nameSpace = Init::$config['controller_namespace'].'\\';
            $className=$nameSpace.explode('@',$path);
            if(class_exists($className)){
               return $className;
            }
            return null;
   }
}