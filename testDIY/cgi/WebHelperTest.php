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

require "../../vendor/autoload.php";

$data = dirname(__DIR__) . "\\_res\\only_resource.txt";
// WebHelper::download($data, "myNewFileName.md");
WebHelper::download($data);

/**
 * 以下代码是演示：在 WebHelper::download 后面还有其他代码，不会影响文件的下载
 */
dump(2222);
