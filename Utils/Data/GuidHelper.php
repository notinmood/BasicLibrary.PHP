<?php
namespace Hiland\Utils\Data;

if (defined("TWOZEROZEROZEROYEAR") == false) {
    // 946656000 表示1970年1月1日到2000年1月1日间总的秒数
    define("TWOZEROZEROZEROYEAR", "946656000");
}

/**
 * GUID类型辅助器
 *
 * @author devel
 *
 */
class GuidHelper
{

    /**
     * 空值guid
     *
     * @param bool $isBracket
     *            是否在guid两端加入括号
     * @return string
     */
    public static function emptys($isBracket = false)
    {
        $guid = "00000000-0000-0000-0000-000000000000";

        if ($isBracket == true) {
            $guid = chr(123) . $guid . chr(125); // "{" // "}"
        }

        return $guid;
    }

    /**
     * 生成新的guid
     *
     * @param bool $isBracket
     *            是否在guid两端加入括号
     * @return string
     */
    public static function newGuid($isBracket = false)
    {
        $totalMilliseconds = (time() - TWOZEROZEROZEROYEAR) * 1000 + DateHelper::getCurrentMilliSecond();
        $totalMillisecondsHex = base_convert($totalMilliseconds, 10, 16);

        // 左侧通过添加0补齐11位
        $totalMillisecondsHex = str_pad($totalMillisecondsHex, 11, 0, STR_PAD_LEFT);

        $charid = md5(uniqid(mt_rand(), true));
        $totalMillisecondsHex = strtoupper($totalMillisecondsHex . substr($charid, 0, 21));

        $hyphen = chr(45); // "-"
        $guid = substr($totalMillisecondsHex, 0, 8) . $hyphen
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
     *
     * @param string $guidWithHyphen
     *            带有分隔符“-”的guid字符串
     * @return string 清除后不带有分隔符“-”的guid字符串
     */
    public static function cleanHyphen($guidWithHyphen)
    {
        $hyphen = chr(45); // "-"
        $result = str_replace($hyphen, '', $guidWithHyphen);
        return $result;
    }

    /**
     * 对没有分隔符的guid字符串，通过分隔符“-”进行格式化
     *
     * @param string $guidWithoutHyphen
     *            没有分隔符的guid字符串
     * @return string 通过分隔符“-”格式化的guid字符串
     */
    public static function addonHyphen($guidWithoutHyphen)
    {
        $guidWithoutHyphen = StringHelper::getSeperatorAfterString($guidWithoutHyphen, chr(123)); // "{"
        $guidWithoutHyphen = StringHelper::getSeperatorBeforeString($guidWithoutHyphen, chr(125)); // "}"

        $hyphen = chr(45); // "-"
        $guid = substr($guidWithoutHyphen, 0, 8) . $hyphen
            . substr($guidWithoutHyphen, 8, 4) . $hyphen
            . substr($guidWithoutHyphen, 12, 4) . $hyphen
            . substr($guidWithoutHyphen, 16, 4) . $hyphen
            . substr($guidWithoutHyphen, 20, 12);

        return $guid;
    }

    /**
     * 判断一个字符串是否为guid格式
     * @param $data
     * @return bool
     */
    public static function determine($data)
    {
        $data = StringHelper::getSeperatorAfterString($data, chr(123)); // "{"
        $data = StringHelper::getSeperatorBeforeString($data, chr(125)); // "}"
        $pattern = RegexHelper::GUID;
        if (preg_match($pattern, $data)) {
            return true;
        } else {
            return false;
        }
    }
}

?>