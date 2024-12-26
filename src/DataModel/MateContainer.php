<?php

namespace Hiland\DataModel;

use Hiland\Config\ConfigClient;
use Hiland\Environment\EnvHelper;
use think\facade\Db;

/**
 * Class MateContainer Mate的容器，可以在一个请求之内重复利用已经创建的 Mate，节省内存和效率
 * @package Hiland\DataModel
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
                /**
                 * 为ORM设置数据库连接
                 */
                /** @noinspection all */
                Db::setConfig(ConfigClient::get("database"));
            }

            $mate = new ModelMate($name);
            Container::set($name, $mate);

            return $mate;
        }
    }
}
