<?php
/**
 * @file   : DeviceTest.php
 * @time   : 19:15
 * @date   : 2021/9/21
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

use Hiland\Utils\Environment\DeviceHelper;
use Hiland\Utils\IO\ConsoleHelper;

require "../vendor/autoload.php";

$isMobile= DeviceHelper::isMobile();
ConsoleHelper::echoBool($isMobile);
