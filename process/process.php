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
        $kill_msg =  \swoole_process::wait(false);
        echo '子进程被杀掉，信息：'.var_export($kill_msg,true);
        $process = self::$process;
        foreach($process as $i => $pid){
            if($pid != $kill_msg['pid']){
                continue;
            }
            self::$process[$i] = 0;
            //如果不是kill -9信号，将会重新拉起被杀掉的进程
            if($kill_msg['signal'] != SIGKILL){
                $new_process = $this->createMarketProcess($i);
                self::$process[$i] = $new_process->pid;
            }
            break;
        }
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


