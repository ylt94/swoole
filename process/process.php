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
    }

    public function createProcess($i){
        $process = new Process(function(\swoole_process $process) use($i){
            echo 'i------>'.$i;
        });
        $process->start();
        return $process;
    }
}

$test = new Test();
$test->index();


