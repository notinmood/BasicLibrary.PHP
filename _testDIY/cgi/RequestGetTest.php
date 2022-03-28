<?php
/**
 * @file   : RequestGetTest.php
 * @time   : 19:08
 * @date   : 2022/1/12
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

use Hiland\Utils\Environment\EnvHelper;
use Hiland\Utils\Web\RequestHelper;
use Hiland\Utils\Web\RequestMethods;

require "../../vendor/autoload.php";

/**
 * 本文件对请求的 get 方法进行验证; 关于 post 的验证，请参看 RequestPostTest.html 和 RequestPostTest.php 文件
 */
$fullPath = RequestHelper::getFullPath();
el("请求的路径为：{$fullPath}");

$gotten = RequestHelper::getInput("_ijt");
el("get到的数据为：{$gotten}");
$posted = RequestHelper::getInput("_ijt", "", RequestMethods::POST);
el("post无法得到数据：{$posted}");
el("────────────────────────");
el("本文件对请求的 get 方法进行验证; 关于 post 的验证，请参看 RequestPostTest.html 和 RequestPostTest.php 文件");