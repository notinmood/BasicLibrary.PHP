<?php

namespace Hiland\Utils\DataModel;

use Hiland\Utils\Config\ConfigHelper;
use Hiland\Utils\Environment\EnvHelper;
use think\facade\Db;

/**
 * Class MateContainer Mate的容器，可以在一个请求之内重复利用已经创建的Mate，节省内存和效率
 * @package Hiland\Utils\DataModel
 */
class MateContainer
{
    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        $result = Container::get($name);

        if ($result) {
            return $result;
        } else {
            /**
             * 如果不是标准的ThinkPHP环境，那么此处就采用ThinkPHP-ORM组件，就需要单独读取数据库配置信息；
             * 否则此处不需要处理，ThinkPHP会自动处理。
             */
            if (EnvHelper::isThinkPHP() == false) {
                self::setConnection();
            }

            $mate = new ModelMate($name);
            Container::set($name, $mate);

            return $mate;
        }
    }

    /**
     * @return void
     */
    private static function setConnection()
    {
        Db::setConfig([
            // 默认数据连接标识
            'default' => 'defaultConnection',
            // 数据库连接信息
            'connections' => [
                'defaultConnection' => [
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
            ],
        ]);
    }
}