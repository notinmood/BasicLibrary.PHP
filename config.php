<?php
/**
 * @file   : demo.config.php
 * @time   : 12:15
 * @date   : 2021/9/5
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

/**
 * 以下内容全部为单元测试的数据。正式项目的时候，可以(连同本句注释)全部删除。
 */

return [
    'a' => 'AAA',
    'b' => false,
    'c' => 123,
    'd' => [
        'dA' => 'dA-content',
        'dB' => [
            'dBA' => 'dba-content',
        ],
    ],

    'office' => ["MS", "WPS"],

    'database' => [
        // 默认使用的数据库连接配置
        'default' => 'mysql',
        // 数据库连接配置信息
        'connections' => [
            'mysql' => [
                // 数据库类型
                'type' => 'mysql',
                // 主机地址
                'hostname' => '127.0.0.1',
                // 用户名
                'username' => 'root',
                // 数据库名
                'database' => 'mydemo',
                // 数据库编码默认采用utf8
                'charset' => 'utf8',
                // 数据库表前缀
                'prefix' => 'tmp_',
                // 数据库调试模式
                'debug' => true,
            ],

            // 更多的数据库配置信息
        ],
    ],

];
