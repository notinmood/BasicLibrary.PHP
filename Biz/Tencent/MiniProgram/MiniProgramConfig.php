<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/9
 * Time: 18:40
 */

namespace Hiland\Biz\Tencent\MiniProgram;

use Hiland\Biz\ThinkAddon\TPCompatibleHelper;
use Hiland\Utils\Data\ObjectHelper;

/**
 * 说明，需要在项目的主配置文件内设置以下两个节点
 * "mimiprogram-appid"对应为小程序的appid
 * "mimiprogram-secret"对应为小程序的secret
 * Class MiniProgramConfig
 * @package Hiland\Biz\Tencent\MiniProgram
 */
class MiniProgramConfig
{
    public static function getAPPID()
    {
        $projectName = input("PN");
        if (ObjectHelper::isEmpty($projectName)) {
            $projectName = "";
        } else {
            $projectName .= "_";
        }

        $wxMiniAppName = input("MAN");//MiniAppName
        if (ObjectHelper::isEmpty($wxMiniAppName)) {
            $wxMiniAppName = "";
        } else {
            $wxMiniAppName .= "_";
        }

        $result = TPCompatibleHelper::config("{$projectName}Machine.WeiXin.{$wxMiniAppName}mimiprogram-appid");

        if (empty($result)) {
            $result = "wxa37839e8d0954603";
        }

        return $result;
    }

    public static function getSECRET()
    {
        $projectName = input("PN");
        if (ObjectHelper::isEmpty($projectName)) {
            $projectName = "";
        } else {
            $projectName .= "_";
        }

        $wxMiniAppName = input("MAN");//MiniAppName
        if (ObjectHelper::isEmpty($wxMiniAppName)) {
            $wxMiniAppName = "";
        } else {
            $wxMiniAppName .= "_";
        }

        $result = TPCompatibleHelper::config("{$projectName}Machine.WeiXin.{$wxMiniAppName}mimiprogram-secret");

        if (empty($result)) {
            $result = "96acf4487c365efb37edd16f5bf1b496";
        }

        return $result;
    }
}
