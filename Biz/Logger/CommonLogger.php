<?php

namespace Hiland\Biz\Logger;

use Hiland\Biz\Logger\DBLogger\Logger;
use Hiland\Biz\ThinkAddon\TPCompatibleHelper;
use Hiland\Biz\ThinkAddon\TPConfigHelper;
use Hiland\Utils\Data\ReflectionHelper;

/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2016/6/30
 * Time: 7:31
 * 说明: 如果启用第三方的loger，请在配置文件中设置LogProviderName
 * C("SYSTEM_LOG_LEVEL") 请配置日志记录级别
 */
class CommonLogger
{
    const LOGLEVEL_BIZ = 0;
    const LOGLEVEL_DEBUG = 30;
    const LOGLEVEL_WARNING = 40;
    const LOGLEVEL_ERROR = 50;
    const LOGLEVEL_DIE = 60;

    /**
     * 进行日志记录
     * @param string $title 日志的标题
     * @param string $content 日志的内容
     * @param int $logLevel 记录的级别  LOGLEVEL_***,缺省为LOGLEVEL_DEBUG
     * @param array $option 日志的其他信息
     *      string $category 日志的分类名称
     *      string $other 日志附加信息
     *      int $misc 日志附加信息
     *      string $status 日志状态信息
     */
    public static function log($title, $content = '', $logLevel = CommonLogger::LOGLEVEL_DEBUG, $option = array('status' => '', 'category' => 'develop', 'other' => '', 'misc' => 0))
    {
        $systemLogLevel = TPCompatibleHelper::config("SYSTEM_LOG_LEVEL");
        if (empty($systemLogLevel)) {
            $systemLogLevel = CommonLogger::LOGLEVEL_DEBUG;
        }

        if ($logLevel > $systemLogLevel) {
            return;
        }

        $status = '';
        if ($option['status']) {
            $status = $option['status'];
        }

        $category = '';
        if ($option['category']) {
            $category = $option['category'];
        }

        $other = '';
        if ($option['other']) {
            $other = $option['other'];
        }

        $misc = 0;
        if ($option['misc']) {
            $misc = $option['misc'];
        }

        self::getLogger()->log($title, $content, $status, $category, $other, $misc);
    }

    private static function getLogger()
    {
        $cacheKey = "system-provider-logger";
        if (TPCompatibleHelper::cache($cacheKey)) {
            return TPCompatibleHelper::cache($cacheKey);
        } else {
            $providerName = TPCompatibleHelper::config("LogProviderName");
            if (empty($providerName)) {
                //$providerName = "DBLogger";
                //默认的logger为DBLogger直接使用new机制，不使用反射机制生成，提高性能
                $logger = new Logger();
            } else {
                $className = "Hiland\\Biz\\Logger\\$providerName\\Logger";
                $logger = ReflectionHelper::createInstance($className);
            }

            TPCompatibleHelper::cache($cacheKey, $logger);
            return $logger;
        }
    }
}