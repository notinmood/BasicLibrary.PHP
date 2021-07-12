<?php

namespace Hiland\Utils\Data;

class DateHelper
{
    /** 比较两个日期的大小
     * @param $dateMain
     * @param $dateSecondary
     * @return int 如果$dateMain大于$dateSecondary返回1；小于返回-1；等于返回0.
     */
    public static function compare($dateMain, $dateSecondary)
    {
        $dateMain = self::parseDateTimeSafely($dateMain);
        $dateSecondary = self::parseDateTimeSafely($dateSecondary);

        if ($dateMain == false || $dateSecondary == false) {
            return 0;
        } else {
            if ($dateMain == $dateSecondary) {
                return 0;
            }

            if ($dateMain > $dateSecondary) {
                return 1;
            } else {
                return -1;
            }
        }
    }

    /**从字符串解析出日期时间
     * @param $dateString
     * @return bool|\DateTime，成功时返回正确的日期时间格式；失败时返回false；
     */
    public static function parseDateTimeSafely($dateString)
    {
        $result = false;
        $type = ObjectHelper::getType($dateString);
        switch ($type) {
            case ObjectTypes::STRING:
                try {
                    $result = new \DateTime($dateString);
                } catch (\Exception $e) {
                    $result = false;
                }
                break;
            case ObjectTypes::DATETIME:
                $result = $dateString;
                break;
            default:
                $result = false;
        }

        return $result;
    }

    /** 将timestamp转换成日期字符串
     * @param null $timestamp
     * @param string $format
     * @return false|string
     */
    public static function getDateTimeString($timestamp = null, $format = "Y-m-d H:i:s")
    {
        if (!$timestamp) {
            $timestamp = self::getTimestamp();
        }

        return date($format, $timestamp);
    }

    /**将timestamp转换成日期
     * @param null $timestamp
     * @return array
     */
    public static function getDateTime($timestamp = null)
    {
        if (!$timestamp) {
            $timestamp = self::getTimestamp();
        }

        return getdate($timestamp);
    }

    /**获取两个日期之间的差值（秒或者毫秒）
     * @param $dateMain
     * @param $dateSecondary
     * @param string $intervalType “s”表示秒；“ms”表示毫秒
     * @return float|int
     */
    public static function diffInterval($dateMain, $dateSecondary, $intervalType = "s")
    {
        $ms4Main = self::getTotalMilliSeconds($dateMain);
        $ms4Secondary = self::getTotalMilliSeconds($dateSecondary);

        $ms4Diff = $ms4Main - $ms4Secondary;

        $result = null;
        switch ($intervalType) {
            case "ms":
                $result = $ms4Diff;
                break;
            default :
                $result = $ms4Diff / 1000;
        }

        return $result;
    }

    /**
     * 获取从1970年1月1日以来总共的毫秒数
     *
     * @return float
     */
    public static function getTotalMilliSeconds($dateValue = null)
    {
        return self::getTimestamp($dateValue) * 1000;
    }

    /**
     * 获取一个指定时间点的timestamp(即从1970年1月1日以来总共的秒数)
     * @param string $dateValue 指定的时间点 ，可以是“201603161312”格式，也可以是“2016-03-16 13:12:25”
     * @return int
     */
    public static function getTimestamp($dateValue = null)
    {
        if (ObjectHelper::isEmpty($dateValue)) {
            $dateValue = new \DateTime();
        }

        if (ObjectHelper::getType($dateValue) == ObjectTypes::STRING) {
            $dateValue = new \DateTime($dateValue);
        }

        //以下代码修复php中2038年问题（32位php的int无法表示2038年01月19日星期二凌晨03:14:07之后的时间秒数。
        //超过 2^31 – 1，2^31 – 1 就是0x7FFFFFFF）
        $year20380101 = new \DateTime("2038-1-1 0:0:0");
        if ($dateValue <= $year20380101) {
            $result = $dateValue->getTimestamp();
        } else {
            $dateDiff = $dateValue->diff($year20380101);
            $days = floatval($dateDiff->days);
            $year20380101Seconds = $year20380101->getTimestamp();
            $totalSeconds = floatval($days * 86400)
                + $dateDiff->h * 3600 + $dateDiff->m * 60 + $dateDiff->s;

            $result = $year20380101Seconds + $totalSeconds;
        }

        return $result;
    }

    /**
     * 获取当前时间的毫秒数信息
     *
     * @return float
     */
    public static function getCurrentMilliSecond()
    {
        return microtime(true) * 1000;
    }

    /**
     * 对日期进行时间间隔处理
     *
     * @param int $originalTimestamp
     *            int类型的时间戳
     * @param string $intervalType
     *            时间间隔类型，具体如下：
     *            y:年
     *            M:月
     *            d:日
     *
     *            q:季度
     *            w:星期
     *
     *            h:小时
     *            m或者i:分钟
     *            s:秒钟
     *
     * @param int $intervalValue
     *            时间间隔值
     * @return int int类型的时间戳
     * @throws \Exception
     */
    public static function addInterval($originalTimestamp = null, $intervalType = "d", $intervalValue = 1)
    {
        if (empty($originalTimestamp)) {
            $originalTimestamp = time();
        }
        $originalTimestamp = "@" . $originalTimestamp;


        $y = $M = $d = $h = $i = $s = 0;
        $invert = 0;
        if ($intervalValue < 0) {
            $invert = 1;
        }
        $intervalValue = abs($intervalValue);

        //$formatString = "P0Y0M0DT0H0M0S";

        switch ($intervalType) {
            case "y":
            case "Y":
                $y = $intervalValue;
                break;
            case "q":
            case "Q":
                $M = $intervalValue * 3;
                break;
            case "M":
                $M = $intervalValue;
                break;
            case "d":
            case "D":
                $d = $intervalValue;
                break;
            case "w":
            case "W":
                $d = $intervalValue * 7;
                break;
            case "h":
            case "H":
                $h = $intervalValue;
                break;
            case "m":
            case "i":
            case "I":
                $i = $intervalValue;
                break;
            case "s":
            case "S":
                $s = $intervalValue;
                break;
        }

        $formatString = "P|$y|Y|$M|M|$d|DT|$h|H|$i|M|$s|S";
        $formatString = str_replace("|", "", $formatString);

        $interval = new \DateInterval($formatString);
        $interval->invert = $invert;

        $source = new \DateTime($originalTimestamp, new \DateTimeZone("PRC"));
        $target = $source->add($interval);

        return $target->getTimestamp();
    }

    /**
     * 对数字表示的timestamp进行格式化友好显示
     * @param int $time timestamp格式的时间
     * @param string $formatString 格式化字符串
     * @return string
     */
    public static function format($time = null, $formatString = 'Y-m-d H:i:s')
    {
        $time = $time === null ? time() : floatval($time);

        return date($formatString, $time);
    }

    /**
     * 获取某个制定的日期是星期几
     * @param $timestamp 指定的日期（默认为当前日期）
     * @param string $format 返回星期几的格式
     * （默认（或者number,N,n）为数组0-7；
     * chinese,C,c:汉字 一，。。。日；
     * chinesefull,CF,cf:汉字全称 星期一。。。星期天）
     * @return string
     */
    public static function getWeekName($format = 'number', $timestamp = null)
    {
        if ($format == 'number' || $format == 'N' || $format == 'n') {
            $format = 'n';
        }

        if ($format == 'chinese' || $format == 'C' || $format == 'c') {
            $format = 'c';
        }

        if ($format == 'chinesefull' || $format == 'CF' || $format == 'cf') {
            $format = 'cf';
        }

        $week = date("w", $timestamp);

        $result = '';
        switch ($week) {
            case 1:
                $result = "一";
                break;
            case 2:
                $result = "二";
                break;
            case 3:
                $result = "三";
                break;
            case 4:
                $result = "四";
                break;
            case 5:
                $result = "五";
                break;
            case 6:
                $result = "六";
                break;
            case 0:
                $result = "日";
                break;
        }

        switch ($format) {
            case "n":
                return $week;
                break;
            case 'c':
                return $result;
                break;
            case 'cf':
                return '星期' . $result;
                break;
        }
    }

    /**
     * 获取日期中月份的中文叫法
     * @param null|int $month
     * @param string $postfixstring 后缀信息
     * @return string
     */
    public static function getMonthChineseName($month = null, $postfixstring = '')
    {
        if ($month == null) {
            $month = date("n");
        }

        switch ($month) {
            case 1:
                $result = '一';
                break;
            case 2:
                $result = '二';
                break;
            case 3:
                $result = '三';
                break;
            case 4:
                $result = '四';
                break;
            case 5:
                $result = '五';
                break;
            case 6:
                $result = '六';
                break;
            case 7:
                $result = '七';
                break;
            case 8:
                $result = '八';
                break;
            case 9:
                $result = '九';
                break;
            case 10:
                $result = '十';
                break;
            case 11:
                $result = '十一';
                break;
            case 12:
                $result = '十二';
                break;
        }

        return $result . $postfixstring;
    }
}