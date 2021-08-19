<?php
/**
 * @file   : version.php
 * @time   : 7:37
 * @date   : 2021/8/10
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

use Hiland\Utils\Environment\EnvHelper;

require "../vendor/autoload.php";

echo phpversion() . PHP_EOL;
echo PHP_VERSION . PHP_EOL;
echo PHP_EXTRA_VERSION;

echo EnvHelper::getPHPVersion() . PHP_EOL;
echo EnvHelper::getPHPWholeVersion() . PHP_EOL;
