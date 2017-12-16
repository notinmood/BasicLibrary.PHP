<?php
namespace Hiland\Utils\Data;

class VerifyHelper
{
    /**
     * 数字验证
     * @param $data
     * @param string $flag : int是否是整数，float是否是浮点型
     * @return bool
     */
    public static function isNumber($data, $flag = 'float')
    {
        if (!self::isEmpty($data)) return false;
        if (strtolower($flag) == 'int') {
            return ((string)(int)$data === (string)$data) ? true : false;
        } else {
            return ((string)(float)$data === (string)$data) ? true : false;
        }
    }

    /**
     * 是否为空值
     * @param $data string
     * @return bool
     */
    public static function isEmpty($data)
    {
        $data = trim($data);
        if (empty($data)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 名称匹配，如用户名，目录名等
     * @param string $data 要匹配的字符串
     * @param bool $chinese 是否支持中文,默认支持，如果是匹配文件名，建议关闭此项（false）
     * @param string $charset 编码（默认utf-8,支持gb2312）
     * @return bool
     */
    public static function isName($data, $chinese = true, $charset = 'utf-8')
    {
        if (!self::isEmpty($data)) return false;
        if ($chinese) {
            $match = (strtolower($charset) == 'gb2312') ? "/^[" . chr(0xa1) . "-" . chr(0xff) . "A-Za-z0-9_-]+$/" : "/^[x{4e00}-x{9fa5}A-Za-z0-9_]+$/u";
        } else {
            $match = '/^[A-za-z0-9_-]+$/';
        }
        return preg_match($match, $data) ? true : false;
    }

    /**
     * 邮箱验证
     * @param $data string
     * @return bool
     */
    public static function isEmail($data)
    {
        if (!self::isEmpty($data)) return false;
        return preg_match(RegexHelper::EMAIL, $data) ? true : false;
    }

    /**
     * 手机号码验证
     * @param $data string
     * @return bool
     */
    public static function isMobile($data)
    {
        $exp = RegexHelper::MOBILE;
        if (preg_match($exp, $data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * URL验证，纯网址格式，不支持IP验证
     * @param $data string
     * @return bool
     */
    public static function isUrl($data)
    {
        if (!self::isEmpty($data)) return false;
        return preg_match(RegexHelper::URL, $data) ? true : false;
    }

    /**
     * 验证中文
     * @param string $data 要匹配的字符串
     * @param string $charset 编码（默认utf-8,支持gb2312）
     * @return bool
     */
    public static function isChinese($data, $charset = 'utf-8')
    {
        if (!self::isEmpty($data)) return false;
        $match = (strtolower($charset) == 'gb2312') ? "/^[" . chr(0xa1) . "-" . chr(0xff) . "]+$/"
            : "/^[x{4e00}-x{9fa5}]+$/u";
        return preg_match($match, $data) ? true : false;
    }

    /**
     * UTF-8验证
     * @param $data string
     * @return bool
     */
    public static function isUtf8($data)
    {
        if (!self::isEmpty($data)) return false;
        return (preg_match("/^([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}/", $data)
            == true || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}$/", $data)
            == true || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){2,}/", $data)
            == true) ? true : false;
    }

    /**
     * 验证长度
     * @param $data
     * @param int $type 方式(默认min <= $str <= max)
     * @param int $min 最小值;
     * @param int $max 最大值;
     * @param string $charset 字符集
     * @return bool
     */
    public static function length($data, $type = 3, $min = 0, $max = 0, $charset = 'utf-8')
    {
        if (!self::isEmpty($data)) return false;
        $len = mb_strlen($data, $charset);
        switch ($type) {
            case 1: //只匹配最小值
                return ($len >= $min) ? true : false;
                break;
            case 2: //只匹配最大值
                return ($max >= $len) ? true : false;
                break;
            default: //min <= $str <= max
                return (($min <= $len) && ($len <= $max)) ? true : false;
        }
    }

    /**
     * 验证密码
     * @param string $data
     * @param int $minLen
     * @param int $maxLen
     * @return boolean
     */
    public static function isPWD($data, $minLen = 6, $maxLen = 16)
    {
        $match = '/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{' . $minLen . ',' . $maxLen . '}$/';
        $v = trim($data);
        if (empty($v))
            return false;
        return preg_match($match, $v);
    }

    /**
     * 验证用户名
     * @param string $data
     * @param int $minLen
     * @param int $maxLen
     * @param string $charset
     * @return boolean
     */
    public static function isNames($data, $minLen = 2, $maxLen = 16, $charset = 'ALL')
    {
        if (empty($data))
            return false;
        switch ($charset) {
            case 'EN':
                $match = '/^[_\w\d]{' . $minLen . ',' . $maxLen . '}$/iu';
                break;
            case 'CN':
                $match = '/^[_\x{4e00}-\x{9fa5}\d]{' . $minLen . ',' . $maxLen . '}$/iu';
                break;
            default:
                $match = '/^[_\w\d\x{4e00}-\x{9fa5}]{' . $minLen . ',' . $maxLen . '}$/iu';
        }
        return preg_match($match, $data);
    }

    /**
     * 验证邮政编码
     * @param string $data 待检测字符串
     * @return bool
     */
    public static function checkZip($data)
    {
        if (strlen($data) != 6) {
            return false;
        }
        if (substr($data, 0, 1) == 0) {
            return false;
        }
        return true;
    }

    /**
     * 匹配日期
     * @param string $data 待检测字符串
     * @return bool
     */
    public static function checkDate($data)
    {
        $dateArr = explode("-", $data);
        if (is_numeric($dateArr[0]) && is_numeric($dateArr[1]) && is_numeric($dateArr[2])) {
            if (($dateArr[0] >= 1000 && $dateArr[0] <= 10000) && ($dateArr[1] >= 0 && $dateArr[1] <= 12) && ($dateArr[2] >= 0 && $dateArr[2] <= 31))
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * 匹配时间
     * @param string $data 待检测字符串
     * @return bool
     */
    public static function checkTime($data)
    {
        $timeArr = explode(":", $data);
        if (is_numeric($timeArr[0]) && is_numeric($timeArr[1]) && is_numeric($timeArr[2])) {
            if (($timeArr[0] >= 0 && $timeArr[0] <= 23) && ($timeArr[1] >= 0 && $timeArr[1] <= 59) && ($timeArr[2] >= 0 && $timeArr[2] <= 59))
                return true;
            else
                return false;
        }
        return false;
    }
}

?>