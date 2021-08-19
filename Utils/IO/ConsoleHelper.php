<?php

namespace Hiland\Utils\IO;

use Hiland\Utils\Data\BoolHelper;
use Hiland\Utils\Environment\EnvHelper;

/**
 * 控制台操作类
 * 处理用户界面的输入输出逻辑
 * @author  解然
 * @version v20131224
 */
class ConsoleHelper
{
    /**
     * 控制台输出
     * @param        $data
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
     * 带换行功能的echo
     * @param mixed $data 待输出信息
     */
    public static function echoLine($data)
    {
        echo "$data", EnvHelper::getNewLineSymbol();
    }

    /**
     * @param string $data
     */
    public static function echoBool($data)
    {
        echo BoolHelper::getText($data);
    }
}