<?php

namespace Core;

use Core\Init;


class Reload{

    public $md5_file;
    public $watch_files;

    public function __construct(){
        foreach(Init::$config['reload_dir'] as $dir){
            $this->watch_files[] = Init::$config['root_path'].'/'.$dir;
        }
        
    }

    public function FilesCheck(){

        $md5 = $this->getMd5();

        $old_md5_flie = $this->md5_file;
        $this->md5_file = $md5;
        
        if($old_md5_flie && $old_md5_flie != $md5){
            echo '检测到文件更新'.PHP_EOL;
            echo '原散列值：'.$old_md5_flie.PHP_EOL;
            echo '现散列值：'.$md5.PHP_EOL;
            return false;
        }
        return true;
    }

    public function getMd5(){
        $md5='';
        //3秒钟之内去比较当前的文件散列值跟上一次文件的散列值
        foreach ($this->watch_files as $dir){
            echo '开始检测：'.$dir.PHP_EOL;
            $md5.= $this->md5File($dir);
        }
        return $md5;
    }
    public function md5File($dir)
    {

        //遍历文件夹当中的所有文件,得到所有的文件的md5散列值
        if (!is_dir($dir)) {
            return '';
        }
        $md5File = array();
        $d       = dir($dir);
        while (false !== ($entry = $d->read())) {

            if ($entry !== '.' && $entry !== '..') {
                if (is_dir($dir . '/' . $entry)) {
                    //递归调用
                    $md5File[] = self::md5File($dir . '/' . $entry);
                } elseif (substr($entry, -4) === '.php') {
                    $md5File[] = md5_file($dir . '/' . $entry);
                }
                $md5File[] = $entry;
            }
        }
        $d->close();
        return md5(implode('', $md5File));
    }
}