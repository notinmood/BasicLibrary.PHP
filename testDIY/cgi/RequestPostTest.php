<?php
/**
 * @file   : RequestPostTest.php
 * @time   : 19:47
 * @date   : 2022/1/12
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


use Hiland\Utils\IO\ConsoleHelper;

displayPostValue("myID");
displayPostValue("myName");
displayPostValue("myJob");
displayPostValue("myCity");

displayPostValue("myLike");
displayPostValue("sex");
echo "this is a demo!";


function getPostValue($name)
{
    $myValue = $_POST[$name];
    if ($myValue) {
        return $myValue;
    } else {
        return "";
    }
}

function displayPostValue($name)
{
    $value = getPostValue($name);
    if ($value) {
        ConsoleHelper::echoLine("YYY-${name}的值为${value}");
    } else {
        ConsoleHelper::echoLine("NNN-不存在 ${name}");
    }
}