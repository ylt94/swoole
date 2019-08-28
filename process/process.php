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
        // Process::signal(SIGCHLD, function($sig) {
        //     //必须为false，非阻塞模式
        //     while($ret =  Process::wait(false)) {
        //         echo "PID={$ret['pid']}\n";
        //     }
        // });
        $this->processWait();
        
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

    public function processWait(){
        $ws_obj = $this;
        \swoole_process::signal(SIGCHLD, function($sig) use($ws_obj) {
            //必须为false，非阻塞模式
            while($kill_msg =  \swoole_process::wait(false)) {
                \Log::info('子进程被杀掉，信息：'.var_export($kill_msg,true));
                $process = $ws_obj::$process;
                foreach($process as $market => $val){
                    foreach($val as $key => $pid){
                        if($pid != $kill_msg['pid']){
                           continue;
                        }
                        unset($ws_obj::$process[$market][$key]);
                        //如果不是kill -9信号，将会重新拉起被杀掉的进程
                        if($kill_msg['signal'] != SIGKILL){
                            $new_process = $ws_obj->createMarketProcess($market);
                            $ws_obj::$process[$market][] = $new_process->pid;
                        }
                        break;
                    }
                }
            }
        });
    }
}

$test = new Test();
$test->index();


