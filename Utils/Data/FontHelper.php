<?php

namespace Hiland\Utils\Data;

class FontHelper
{
    /**
     * 获取字符串设置为某种字体后的宽带信息
     * @param int|string $font 字体
     * @param int $fontSize 字号大小
     * @param string $string 要测量的字符串
     * @return int
     */
    public static function getWidth($font, $fontSize, $string)
    {
        $array = self::getSize($font, $fontSize, $string);
        return $array[0];
    }

    /**
     * 获取字符串设置为某种字体后的size信息
     * @param int|string $font 字体
     * @param int $fontSize 字号大小
     * @param string $string 要测量的字符串
     * @return number[] 一维二元数组 元素0位宽带信息，元素1位高度信息
     */
    public static function getSize($font, $fontSize, $string)
    {
        if (is_numeric($font)) {
            $fontWidth = imagefontwidth($font);
            $fontHeight = imagefontheight($font);

            // 计算字体所占宽高
            $word_length = strlen($string);
            $wholeWidth = $fontWidth * $word_length;
            $wholeHeight = $fontHeight;
        } else {
            $arr = imagettfbbox($fontSize, 0, $font, $string);
            $wholeWidth = abs($arr[0] - $arr[2]);
            $wholeHeight = abs($arr[7] - $arr[1]);
        }

        return array(
            $wholeWidth,
            $wholeHeight
        );
    }

    /**
     * 获取字符串设置为某种字体后的高度信息
     * @param int|string $font 字体
     * @param int $fontSize 字号大小
     * @param string $string 要测量的字符串
     * @return int
     */
    public static function getHeight($font, $fontSize, $string)
    {
        $array = self::getSize($font, $fontSize, $string);
        return $array[1];
    }
}