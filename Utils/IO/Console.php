<?php

namespace Hiland\Utils\IO;

/**
 * 控制台操作类
 * 处理用户界面的输入输出逻辑
 * @author 解然
 * @version v20131224
 */
class Console
{
    /**
     * 带换行功能的echo (本函数将取消，请使用echoLine)
     * @param mixed $data 待输出信息
     */
    public static function echos($data)
    {
        echo $data, "<br/>";
    }

    /**
     * 带换行功能的echo
     * @param mixed $data 待输出信息
     */
    public static function echoLine($data)
    {
        echo $data, "<br/>";
    }

    /**
     *
     * @param string $data
     */
    public static function echoBool($data)
    {
        echo self::getBoolString($data);
    }

    /**
     * 将bool类型转换成字符串
     * @param bool $data
     * @return string
     */
    public static function getBoolString($data)
    {
        $result = "false";
        if ($data == true) {
            $result = "true";
        }
        return $result;
    }
}