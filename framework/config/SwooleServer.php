<?php

namespace Config;



return [

    /**
     * swoole http server config
     */
    'http' => [
        'server' => [
            'host'=> '127.0.0.1',
            'port'=> '9800',
            'enable_tcp'=>1,
            'setting' => [
                'pack_max_length'=>1024*1024*2,
                'worker_num'=>3,
            ]
        ],
        'client' => []
    ],

    'tcp'=>[
        'server' => [
            'host' => '0.0.0.0',                 //服务监听ip
            'port' => 8099,                      //监听端口
            'setting' => [                       //swoole配置
                'worker_num' => 1,               //worker进程数量
                'pack_max_length'=>1024*1024*2,
                'worker_num'=>3,
            ]
        ],
         
        'client' => []
        
    ],
    'web_socket'=>[
        'server' =>[
            'host' => '0.0.0.0',                //服务监听ip
            'port' => 9800,                     //监听端口
            'tcpable'=>1,                       //是否开启tcp监听
            'enable_http' => true,              //是否开启http服务
            'setting' => [                      //swoole配置
                'worker_num' => 2,              //worker进程数量
                'daemonize' => 0,               //是否开启守护进程
                'pack_max_length'=>1024*1024*2,
                // 'upload_tmp_dir'=>__DIR__."/upload",
                // 'document_root' =>__DIR__,
                // 'enable_static_handler' => true
            ]
        ], 
        
        'client' =>[]
        
    ]


];