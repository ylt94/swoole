<?php

go(function () {
    $server = new Co\Http\Server("127.0.0.1", 9500, false);
    $server->handle('/', function ($request, $ws) {
        $ws->upgrade();
        while (true) {
            $frame = $ws->recv();
            if ($frame === false) {
                echo "error : " . swoole_last_error() . "\n";
                break;
            } else if ($frame == '') {
                break;
            } else {
                $ws->push("Hello {$frame->data}!");
                $ws->push("How are you, {$frame->data}?");
            }
        }
    });
    $server->start();
});