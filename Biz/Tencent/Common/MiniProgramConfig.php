<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/9
 * Time: 18:40
 */

namespace Hiland\Biz\Tencent\Common;


use Hiland\Biz\ThinkFix\TPVersionHelper;
use Hiland\Biz\ThinkFix\VersionHelper;

/**
 * 说明，需要在项目的主配置文件内设置以下两个节点
 * "mimiprogram-appid"对应为小程序的appid
 * "mimiprogram-secret"对应为小程序的secret
 *
 * Class MiniProgramConfig
 * @package Hiland\Biz\Tencent\Common
 */
class MiniProgramConfig
{
    public static function getAPPID()
    {
        if (TPVersionHelper::getPrimaryVersion() > 3) {
            $result = config('mimiprogram-appid');
        } else {
            $result = C("mimiprogram-appid");
        }

        if (empty($result)) {
            $result = "wxa37839e8d0954603";
        }

        return $result;
    }

    public static function getSECRET()
    {
        if (TPVersionHelper::getPrimaryVersion() > 3) {
            $result = config('mimiprogram-secret');
        } else {
            $result = C("mimiprogram-secret");
        }

        if (empty($result)) {
            $result = "96acf4487c365efb37edd16f5bf1b496";
        }

        return $result;
    }
}