<?php

use Swoole\Process;
use Swoole\Process\Pool;

class Test{


    protected static $process = [];

    public function index(){
        for($i = 0; $i < 3; $i++){
            $process = $this->createProcess($i);
            self::$process[$i] = $process->pid;          
        }
        var_dump(self::$process);
        Process::signal(SIGCHLD, function($sig) {
            //必须为false，非阻塞模式
            while($ret =  Process::wait(false)) {
                echo "PID={$ret['pid']}\n";
            }
        });
    }

    public function createProcess($i){
        $process = new Process(function(\swoole_process $process) use($i){
            while(true){
                sleep(10);
            }
        });
        $process->start();
        return $process;
    }
}

$test = new Test();
$test->index();


