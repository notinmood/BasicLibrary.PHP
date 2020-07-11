<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/18
 * Time: 19:22
 */

namespace Hiland\Biz\Baidu;
use Hiland\Biz\ThinkAddon\TPCompatibleHelper;

class MiniProgramConfig
{
    public static function getAPPID()
    {
        $result = TPCompatibleHelper::config("anlianMachine.BaiDu.mimiprogram-appid");

        if (empty($result)) {
            $result = "14768308";
        }

        return $result;
    }

    public static function getAPPKEY()
    {
        $result = TPCompatibleHelper::config("anlianMachine.BaiDu.mimiprogram-appkey");

        if (empty($result)) {
            $result = "zHempCohziKG7AkGZ3kGVDFIbKjyvMgV";
        }

        return $result;
    }

    public static function getSECRET()
    {
        $result = TPCompatibleHelper::config("anlianMachine.BaiDu.mimiprogram-secret");

        if (empty($result)) {
            $result = "auIuVIChGE80HNgUU5kjcMp0CFtNMcla";
        }

        return $result;
    }
}