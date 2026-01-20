<?php
/*
 * Copyright (c) General. 2022-2026. All rights reserved.
 * @file     : main.php
 * @time     : 20:09:54
 * @date     : 2026/01/20
 * @mail     : 9727005@qq.com
 * @creator  : ShanDong Xiedali
 * @objective: Less is more. Simple is best!
 */

namespace Hiland\Tools;
require "../vendor/autoload.php";


// 启动主函数，传入目标目录
$targetDirectory = "../src";
$fileExtensions  = [".php", ".ts"];
//$fileExtensions = ".php,.ts,.tsx";

ItemListGenerator::generate($targetDirectory, $fileExtensions);
echo "处理完成时间：" . date('Y-m-d H:i:s') . "\n";