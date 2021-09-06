<?php
/**
 * @file   : ConfigParser.php
 * @time   : 8:39
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

abstract class ConfigParser
{
    public function toString(){
        return get_called_class();
    }

    // public function get($key){
    //
    // }

    /**
     * 从存储系统载入配置文件,并形成array数组返回
     * @param $fileFullName
     */
    public function loadFileToArray($fileFullName){

    }
}