<?php

namespace Hiland\Utils\Data;

class ByteHelper
{
    /**
     * 格式化（友好地显示）字节大小
     * @param number $size      字节数
     * @param string $delimiter 数字和单位分隔符
     * @return string 格式化后的带单位的大小
     */
    public static function displayFriendly($size, string $delimiter = ''): string
    {
        $units = array(
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
            'PB',
        );
        for ($i = 0; $size >= 1024 && $i < 5; $i++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * 转换一个String字符串为byte数组
     * @param string $stringData 需要转换的字符串
     * @return array 目标byte数组
     * @author Zikie
     */
    public static function convertFromString(string $stringData): array
    {
        $bytes  = array();
        $length = strlen($stringData);
        for ($i = 0; $i < $length; $i++) {
            $bytes[] = ord($stringData[$i]);
        }
        return $bytes;
    }

    /**
     * 将字节数组转化为String类型的数据
     * @param array $bytesData 字节数组
     * @return string 一个String类型的数据
     */
    public static function convertToString(array $bytesData): string
    {
        $str = '';
        foreach ($bytesData as $ch) {
            $str .= chr($ch);
        }

        return $str;
    }
}