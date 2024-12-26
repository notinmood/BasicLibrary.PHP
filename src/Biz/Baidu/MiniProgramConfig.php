<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/18
 * Time: 19:22
 */

namespace Hiland\Biz\Baidu;
use Hiland\Biz\ThinkAddon\TPCompatibleHelper;
use Hiland\Data\ObjectHelper;

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
        $result = TPCompatibleHelper::config("{$projectName}Machine.BaiDu.mimiprogram-appid");

        if (empty($result)) {
            $result = "14768308";
        }

        return $result;
    }

    public static function getAPPKEY()
    {
        $projectName = input("PN");
        if (ObjectHelper::isEmpty($projectName)) {
            $projectName = "";
        } else {
            $projectName .= "_";
        }
        $result = TPCompatibleHelper::config("{$projectName}Machine.BaiDu.mimiprogram-appkey");

        if (empty($result)) {
            $result = "zHempCohziKG7AkGZ3kGVDFIbKjyvMgV";
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
        $result = TPCompatibleHelper::config("{$projectName}Machine.BaiDu.mimiprogram-secret");

        if (empty($result)) {
            $result = "auIuVIChGE80HNgUU5kjcMp0CFtNMcla";
        }

        return $result;
    }
}