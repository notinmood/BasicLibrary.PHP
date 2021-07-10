<?php

namespace Hiland\Utils\IO;

use Hiland\Utils\Environment\EnvHelper;

/**
 * 控制台操作类
 * 处理用户界面的输入输出逻辑
 * @author 解然
 * @version v20131224
 */
class DisplayHelper
{
    /**
     * 控制台输出
     * @param $data
     * @param string $level
     */
    public static function console($data, $level = 'log')
    {
        if (is_array($data) || is_object($data)) {
            $output = json_encode($data);
            $jsonDecode = json_decode($output);
            if (empty($jsonDecode) && !empty($data)) {
                echo "<script>console.{$level}('不支持输出')</script>";
                return;
            }
        } elseif (is_string($data)) {
            $output = '"' . $data . '"';
        } else {
            $output = $data;
        }
        echo "<script>console.{$level}({$output})</script>";
        return;
    }

    /**
     * 带换行功能的echo (本函数将取消，请使用echoLine)
     * @param mixed $data 待输出信息
     */
    public static function echos($data)
    {
        if(EnvHelper::isCLI()){
            echo $d,"\r\n";
        }else{
            echo $data, "<br/>";
        }
    }

    /**
     * 带换行功能的echo
     * @param mixed $data 待输出信息
     */
    public static function echoLine($data)
    {
        if(EnvHelper::isCLI()){
            echo $d,"\r\n";
        }else{
            echo $data, "<br/>";
        }
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