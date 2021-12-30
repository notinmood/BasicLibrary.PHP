<?php
/**
 * @file   : WebHelperTest.php
 * @time   : 11:52
 * @date   : 2021/9/22
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

use Hiland\Utils\Web\WebHelper;

require "../vendor/autoload.php";

$data=  __DIR__ ."\\_res\\only_resource.txt";
WebHelper::download($data,"myNewFileName.md");
dump(2222);
