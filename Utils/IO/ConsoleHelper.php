<?php

namespace Hiland\Utils\IO;

use Hiland\Utils\Data\BoolHelper;
use Hiland\Utils\Environment\EnvHelper;

/**
 * 控制台操作类
 * 处理用户界面的输入输出逻辑
 * @author  山东解大劦
 * @version v20131224
 */
class ConsoleHelper
{
    /**
     * 控制台输出
     * @param        $data
     * @param string $level
     */
    public static function console($data, string $level = 'log')
    {
        if (is_array($data) || is_object($data)) {
            $output     = json_encode($data);
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
     * @param mixed $data               待输出信息
     * @param bool  $bothBeforeAndAfter 如果true就在$data这一行前后都加入新行标志;
     *                                  如果false就只在$data这一行后加入新行标志.
     */
    public static function echoLine($data = "", bool $bothBeforeAndAfter = false)
    {
        if ($bothBeforeAndAfter) {
            echo EnvHelper::getNewLineSymbol();
        }

        echo "$data", EnvHelper::getNewLineSymbol();
    }

    /**
     * 带换行功能的echo(方法echoLine的别名)
     * @param mixed $data               待输出信息
     * @param bool  $bothBeforeAndAfter 如果true就在$data这一行前后都加入新行标志;
     *                                  如果false就只在$data这一行后加入新行标志.
     */
    public static function el($data = "", bool $bothBeforeAndAfter = false)
    {
        self::echoLine($data, $bothBeforeAndAfter);
    }

    /**
     * @param string $data
     */
    public static function echoBool(string $data)
    {
        echo BoolHelper::getText($data);
    }
}
