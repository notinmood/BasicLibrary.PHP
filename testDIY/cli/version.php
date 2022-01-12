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

require "../../vendor/autoload.php";

echo phpversion() . EnvHelper::getNewLineSymbol();
echo PHP_VERSION . EnvHelper::getNewLineSymbol();
echo PHP_EXTRA_VERSION . EnvHelper::getNewLineSymbol();

echo EnvHelper::getPHPFloatVersion() . EnvHelper::getNewLineSymbol();
echo EnvHelper::getPHPWholeVersion() . EnvHelper::getNewLineSymbol();
