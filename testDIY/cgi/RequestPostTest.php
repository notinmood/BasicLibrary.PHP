<?php
/**
 * @file   : RequestPostTest.php
 * @time   : 19:47
 * @date   : 2022/1/12
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

require "../../vendor/autoload.php";

use Hiland\Utils\IO\ConsoleHelper;
use Hiland\Utils\Web\RequestHelper;
use Hiland\Utils\Web\RequestMethods;

/**
 * 本文件对请求的 post 方法进行验证
 * 不要直接运本页面。请点击 RequestPostTest.html 页面的提交按钮,自动跳转到本页面。
 */

displayPostValue("myID");
displayPostValue("myName");
displayPostValue("myJob");
displayPostValue("myCity");

displayPostValue("myLike");
displayPostValue("sex");


function getPostValue($name)
{
    $myValue = RequestHelper::getInput($name, "", RequestMethods::POST);
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