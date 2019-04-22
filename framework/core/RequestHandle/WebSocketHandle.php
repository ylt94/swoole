<?php

namespace Core\RequestHandle;

use Core\Route;
use Core\Init;

class WebSocketHandle{
    public static function  open($server,$request,$path){
        try{
            //通过路径得到类名然后实例化
            $class_and_method=self::getClassAndMethod($path);

            $result = null;
            if($class_and_method != null){
                $obj=new $class_and_method['class'];
                $result= $obj->$class_and_method['method']($server);
            }
            return $result;


        }catch (\Throwable $t){
            var_dump($t->getMessage());
        }
   }

   public static function  message(){

   }

   public static  function  close(){

   }

   public static function getClassAndMethod($path=null){
            if($path==null){
               return null;
            }

            $initNameSpace = Init::$config['controller_namespace'].'\\';
            $class_and_method = Route::getNameSpaceByPath($path);
            if(!$class_and_method){
                return null;
            }

            $class = $initNameSpace . explode('@',$class_and_method)[0];
            $method = explode('@',$class_and_method)[1];
            
            return ['class' => $class,'method'=> $method ];
            
   }
}