<?php

namespace Config;



return [

    /**
     * swoole http server config
     */
    'swoole_http' => [
        'server' => [
            'host'=> '127.0.0.1',
            'port'=> '9800',
            'config' => [
                'pack_max_length'=>1024*1024*2,
                'worker_num'=>3,
            ]
        ],
        'client' => []
    ],


];