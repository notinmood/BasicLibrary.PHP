<?php

namespace Hiland\Data;

if (!defined("TWOZEROZEROZEROYEAR")) {
    /**
     * 946656000 表示1970年1月1日到2000年1月1日间总的秒数
     */
    define("TWOZEROZEROZEROYEAR", "946656000");
}

/**
 * GUID类型辅助器
 * @author devel
 */
class GuidHelper
{
    /**
     * 空值guid
     * @param bool $isBracket 是否在guid两端加入括号
     * @return string
     */
    public static function genEmpty(bool $isBracket = false): string
    {
        $guid = "00000000-0000-0000-0000-000000000000";

        if ($isBracket) {
            $guid = chr(123) . $guid . chr(125); // "{" // "}"
        }

        return $guid;
    }

    /**
     * 生成新的guid
     * @param bool $isBracket 是否在guid两端加入括号
     * @return string
     */
    public static function newGuid(bool $isBracket = false): string
    {
        $totalMilliseconds    = (string)((time() - TWOZEROZEROZEROYEAR) * 1000 + DateHelper::getCurrentMilliSecond());
        $timespanString       = StringHelper::splice($totalMilliseconds, ".", "");
        $totalMillisecondsHex = base_convert($timespanString, 10, 16);

        // 左侧通过添加 0 补齐 11 位
        $totalMillisecondsHex = str_pad($totalMillisecondsHex, 11, 0, STR_PAD_LEFT);

        $charID               = md5(uniqid(mt_rand(), true));
        $totalMillisecondsHex = strtoupper($totalMillisecondsHex . substr($charID, 0, 21));

        $hyphen = chr(45); // "-"
        $guid   = substr($totalMillisecondsHex, 0, 8) . $hyphen
            . substr($totalMillisecondsHex, 8, 4) . $hyphen
            . substr($totalMillisecondsHex, 12, 4) . $hyphen
            . substr($totalMillisecondsHex, 16, 4) . $hyphen
            . substr($totalMillisecondsHex, 20, 12);

        if ($isBracket == true) {
            $guid = chr(123) . $guid . chr(125); // "{"// "}"
        }

        return $guid;
    }

    /**
     * 清除guid内部的分隔符“-”
     * @param string $guidWithHyphen 带有分隔符 “-” 的 guid 字符串
     * @return string 清除后不带有分隔符“-”的guid字符串
     */
    public static function cleanHyphen(string $guidWithHyphen): string
    {
        $hyphen = chr(45); // "-"
        return str_replace($hyphen, '', $guidWithHyphen);
    }

    /**
     * 对没有分隔符的guid字符串，通过分隔符“-”进行格式化
     * @param string $guidWithoutHyphen
     *            没有分隔符的guid字符串
     * @return string 通过分隔符“-”格式化的guid字符串
     */
    public static function addonHyphen(string $guidWithoutHyphen): string
    {
        $guidWithoutHyphen = StringHelper::getStringAfterSeparator($guidWithoutHyphen, chr(123));  // "{"
        $guidWithoutHyphen = StringHelper::getStringBeforeSeparator($guidWithoutHyphen, chr(125)); // "}"

        $hyphen = chr(45); // "-"
        return substr($guidWithoutHyphen, 0, 8) . $hyphen
            . substr($guidWithoutHyphen, 8, 4) . $hyphen
            . substr($guidWithoutHyphen, 12, 4) . $hyphen
            . substr($guidWithoutHyphen, 16, 4) . $hyphen
            . substr($guidWithoutHyphen, 20, 12);
    }

    /**
     * 判断一个字符串是否为guid格式
     * @param $data
     * @return bool
     */
    public static function determine($data): bool
    {
        $data    = StringHelper::getStringAfterSeparator($data, chr(123));  // "{"
        $data    = StringHelper::getStringBeforeSeparator($data, chr(125)); // "}"
        $pattern = RegexHelper::GUID;
        if (preg_match($pattern, $data)) {
            return true;
        } else {
            return false;
        }
    }
}
