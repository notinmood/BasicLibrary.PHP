<?php
/**
 * @file   : Student.php
 * @time   : 12:29
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\_res;

class Student
{
    public function __construct($userName, $age)
    {
        $this->age = $age;
        $this->userName = $userName;
    }

    private $age;
    private $userName;

    public function getUserName(){
        return $this->userName;
    }

    private function setUserName($newUserName){
        $this->userName= $newUserName;
    }

    public static function getTypeName(){
        return "这是一个学生";
    }

    private static function getTypeNameEn($fixer=""){
        return "This is a Student ".$fixer;
    }

    public $only4Object2Array= "only4Object2Array";
    public static  $staticOnly4Object2Array = "staticOnly4Object2Array";
}