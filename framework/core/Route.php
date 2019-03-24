<?php

namespace Core;

use Core\Init;

class Route{

    private static $instance;
    private static $route;

    public function __construct(){
        return self::getInstance();
    }

    public function __clone(){
        return self::getInstance();
    }

    public static function getInstance(){
        if(!self::$instance || !is_object(self::$instance)){
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function get(string $path, $params){
        self::$route['get'][$path] = $params;
    }

    public static function post(string $path, $params){
        self::$route['post'][$path] = $params;
    }

}