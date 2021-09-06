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

    public function get($key){

    }

    public function loadFileToArray($fileFullName){

    }
}