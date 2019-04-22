<?php

namespace Core\RequestHandle;

use Core\Route;

class HttpHandle{
    

    /**
     * @throws \Exception
     * @desc 自动路由
     */
    public static function dispatch($request,$response){
            $server=$request->server;
            //获取到默认的路由做一个自动加载
            if ('/favicon.ico' == $server['path_info']) {
                 return '';
             }
             //地址栏地址
             $method=$server['request_method'];
             $path=$server['path_info'];

             switch ($method){
                 case 'GET':
                     //遍历路由表
                     foreach (Route::$route[$method] as $v){
                           //判断路径是否在已经注册的路由上
                           if(in_array($path,$v)){
                               return  self::get($request,$response,$v[0],$v[1]);
                           }
                     }
                     break;
                 case 'POST':
                     break;
             }
    }

    /**
     * get请求处理
     */
    private static function get($request,$response,$path,$param){

        if($param instanceof \Closure){
            //判断是不是一个闭包
            //$result=$param();
            $result=call_user_func($param);
        }else{
            $namespaces=explode('@', $param); //@分隔执行不同的文件

            $class = Init::$config['controller_namespace'].'\\'.$namespaces[0].'Controller'; 
            $method = $namespaces[1];
           
            $result=(new $class)->$method($request);
        }
        $response->end($result);
    }

    /**
     * post请求处理
     */
    private static function post($request,$response,$path,$param){

        if($param instanceof \Closure){
            //判断是不是一个闭包
            //$result=$param();
            $result=call_user_func($param);
        }else{
            $namespaces=explode('@', $param); //@分隔执行不同的文件

            $class = Init::$config['app_namespace'].'\\Controller\\'.$namespaces[0].'Controller'; 
            $method = $namespaces[1];
           
            $result=(new $class)->$method($request);
        }
        $response->end($result);
    }

    /**
     * 不区分请求方式
     */
    private static function any(){}
}