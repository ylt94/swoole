<?php

namespace Core;

class Init{
    
    public static $config;

    public function __construct(array $config){
        self::$config = $config;
    }
}