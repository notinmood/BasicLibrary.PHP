<?php

namespace Hiland\Data;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


// +--------------------------------------------------------------------------
// |::说明：| 1.在 php.ini 内设置 date.timezone 为 Asia/Shanghai
// |·······| 2.修复 2038年问题。( 2038年问题的核心是 timestamp 最大支持 2^31 - 1(即 2,147,483,641)，超过这个值都会出现问题。)
// +--------------------------------------------------------------------------

//TODO:xiedali@2023-11-05 需要加入农历的获取

/**
 * Class DateHelper
 * @package Hiland\Data
 */
class DateHelper
{
    /**
     * 获取 2038年 1月 1日 0时 0分 0秒的时间戳
     * @return int
     */
    private static function get20380101Timestamp(): int
    {
        return 2145888000;
    }

    /**
     * 20380101 的时间表示格式
     * @return DateTime
     */
    private static function get20380101DateTime(): DateTime
    {
        $result = null;
        try {
            $result = new DateTime("2038-1-1 0:0:0", self::getTimeZoneObject());
        } catch (Exception $e) {
            //do nothing;
        }

        return $result;
    }


    /**
     * 比较两个日期的大小
     * @param $dateMain
     * @param $dateSecondary
     * @return int 如果$dateMain大于$dateSecondary返回1；小于返回-1；等于返回0.
     */
    public static function compare($dateMain, $dateSecondary): int
    {
        $dateMain      = self::parseDateTimeSafely($dateMain);
        $dateSecondary = self::parseDateTimeSafely($dateSecondary);

        if (!$dateMain || !$dateSecondary) {
            return 0;
        }

        if ($dateMain == $dateSecondary) {
            return 0;
        }

        if ($dateMain > $dateSecondary) {
            return 1;
        }

        return -1;
    }

    /**
     * 从字符串等信息中解析出日期时间
     * @param $data mixed 可以是一个字符串,数字,日期格式
     * @return bool|DateTime 成功时返回正确的日期时间格式；失败时返回false；
     */
    public static function parseDateTimeSafely($data)
    {
        if (ObjectHelper::isEmpty($data)) {
            return false;
        }

        $type = ObjectHelper::getTypeName($data);
        switch ($type) {
            case ObjectTypes::STRING:
                try {
                    $result = new DateTime($data, self::getTimeZoneObject());
                } catch (Exception $e) {
                    $result = false;
                }
                break;
            case ObjectTypes::DATETIME:
                $result = $data;
                break;
            case ObjectTypes::INTEGER:
            case ObjectTypes::DOUBLE:
            case ObjectTypes::FLOAT:
                try {
                    $result = self::getDateTime($data);
                } catch (Exception $e) {
                    $result = false;
                }
                break;
            default:
                $result = false;
        }

        return $result;
    }

    /**
     * 将 timestamp 转换成日期字符串( format 函数的别名)
     * @param null $timestamp
     * @param string $format
     * @return string
     */
    public static function getDateTimeString($timestamp = null, string $format = "Y-m-d H:i:s"): string
    {
        return self::format($timestamp, $format);
    }

    /**
     * 将 timestamp 转换成日期
     * @param null $timestamp
     * @return float|DateTime|int|null
     */
    public static function getDateTime($timestamp = null): float|DateTime|int|null
    {
        $timestamp = $timestamp === null ? time() : (float)$timestamp;

        if ($timestamp <= self::get20380101Timestamp()) {
            $targetArray = getdate($timestamp);

            $targetString = "{$targetArray['year']}-{$targetArray['mon']}-{$targetArray['mday']} {$targetArray['hours']}:{$targetArray['minutes']}:{$targetArray['seconds']}";
            try {
                return new DateTime($targetString, self::getTimeZoneObject());
            } catch (Exception) {
                return null;
            }
        } else {
            try {
                $ts2038    = self::get20380101Timestamp();
                $diffArray = self::getInterval($ts2038, $timestamp);

                $targetDateTime = self::addInterval($ts2038, 'd', $diffArray->d, 'dt');
                $targetDateTime = self::addInterval($targetDateTime, 'h', $diffArray->h, 'dt');
                $targetDateTime = self::addInterval($targetDateTime, 'm', $diffArray->i, 'dt');
                return self::addInterval($targetDateTime, 's', $diffArray->s, 'dt');
            } catch (Exception) {
                return null;
            }
        }
    }

    /**
     * 获取两个日期之间的差值（秒或者毫秒）
     * @param        $dateMain
     * @param        $dateSecondary
     * @param string $intervalType “s”表示秒；“ms”表示毫秒
     * @return float|int
     * @throws Exception
     */
    public static function getIntervalSeconds($dateMain, $dateSecondary, string $intervalType = "s"): float|int
    {
        $ms4Main      = self::getTotalMilliSeconds($dateMain);
        $ms4Secondary = self::getTotalMilliSeconds($dateSecondary);

        $ms4Diff = $ms4Main - $ms4Secondary;

        return match ($intervalType) {
            "ms" => $ms4Diff,
            default => $ms4Diff / 1000,
        };
    }


    /**
     * 获取两个日期之间的差额(如果后一个日期比前一个日期小,那么返回的DateInterval的属性invert的值为1)
     * @param $startValue float|DateTime|int 开始时间(即可以是DateTime类型也可以是timestamp类型)
     * @param $endValue   float|DateTime|int 结束时间(即可以是DateTime类型也可以是timestamp类型)
     * @return DateInterval|null
     * @throws Exception
     */
    public static function getInterval(float|DateTime|int $startValue, float|DateTime|int $endValue): ?DateInterval
    {
        if (ObjectHelper::getTypeName($startValue) === ObjectTypes::DATETIME) {
            $startValue = self::getTimestamp($startValue);
        }

        if (ObjectHelper::getTypeName($endValue) === ObjectTypes::DATETIME) {
            $endValue = self::getTimestamp($endValue);
        }

        $timeDiff = $endValue - $startValue;
        $invert   = 0;

        if ($timeDiff < 0) {
            $invert   = 1;
            $timeDiff = 0 - $timeDiff;
        }

        $days    = (int)($timeDiff / 86400);
        $remain  = $timeDiff % 86400;
        $hours   = (int)($remain / 3600);
        $remain  %= 3600;
        $minutes = (int)($remain / 60);
        $seconds = $remain % 60;

        /**
         * 间隔规格格式以字母P开头，用于“期间”。每个持续时间周期由一个整数值和一个句点指示符表示。
         * 如果持续时间包含时间元素，则说明的该部分前面有字母T。
         */
        $p        = "P{$days}DT{$hours}H{$minutes}M{$seconds}S";
        $interval = null;
        try {
            $interval = new DateInterval($p);
        } catch (Exception $e) {
        }
        $interval->invert = $invert;
        return $interval;
    }

    /**
     * 获取从1970年1月1日以来总共的毫秒数
     * @param null $dateValue
     * @return float|int
     * @throws Exception
     */
    public static function getTotalMilliSeconds($dateValue = null): float|int
    {
        return self::getTimestamp($dateValue) * 1000;
    }

    /**
     * 获取一个指定时间点的timestamp(即从1970年1月1日以来总共的秒数)
     * @param mixed|null $dateValue 指定的时间点 ，可以是“201603161312”格式，也可以是“2016-03-16 13:12:25”
     * @return float|int
     * @throws Exception
     */
    public static function getTimestamp(mixed $dateValue = null): float|int
    {
        if (ObjectHelper::isEmpty($dateValue)) {
            $dateValue = new DateTime();
        }

        if (ObjectHelper::getTypeName($dateValue) === ObjectTypes::STRING) {
            try {
                $dateValue = new DateTime($dateValue);
            } catch (Exception $e) {
            }
        }
        $dateValue->setTimezone(self::getTimeZoneObject());

        //以下代码修复php中2038年问题（32位php的int无法表示2038年01月19日星期二凌晨03:14:07之后的时间秒数。
        //超过 2^31 – 1，2^31 – 1 就是0x7FFFFFFF）
        $year20380101 = self::get20380101DateTime(); //new DateTime("2038-1-1 0:0:0", self::getDateTimeZone());
        if ($dateValue <= $year20380101) {
            $result = $dateValue->getTimestamp();
        } else {
            $dateDiff     = $dateValue->diff($year20380101);
            $days         = (float)$dateDiff->days;
            $totalSeconds = $days * 86400
                + $dateDiff->h * 3600 + $dateDiff->i * 60 + $dateDiff->s;

            $year20380101Seconds = $year20380101->getTimestamp();

            $result = $year20380101Seconds + $totalSeconds;
        }

        return $result;
    }

    /**
     * 获取当前时间的毫秒数信息
     * @return float|int
     */
    public static function getCurrentMilliSecond(): float|int
    {
        return microtime(true) * 1000;
    }

    /**
     * 对日期进行时间间隔处理
     * @param null $originalValue int类型的时间戳或者是DateTime时间
     * @param string $intervalType
     *                                    时间间隔类型，具体如下：
     *                                    y:年
     *                                    M:月
     *                                    d:日
     *                                    q:季度
     *                                    w:星期
     *                                    h:小时
     *                                    m或者i:分钟
     *                                    s:秒钟
     * @param int $intervalValue 时间间隔值
     * @param string $returnType 返回值类型--dt:返回DateTime时间类型；ts(默认):返回timestamp类型。
     * @return float|DateTime|int int类型的时间戳或者是DateTime时间
     * @throws Exception
     */
    public static function addInterval($originalValue = null, string $intervalType = "d", int $intervalValue = 1, string $returnType = "ts"): float|DateTime|int
    {
        if (ObjectHelper::getTypeName($originalValue) === ObjectTypes::DATETIME) {
            $source = $originalValue;
        } else {
            if ($originalValue) {
                $originalValue = "@" . $originalValue;
            }

            $source = null;
            try {
                $source = new DateTime($originalValue);
            } catch (Exception $e) {
            }
            $source->setTimezone(self::getTimeZoneObject());
        }


        $y      = $M = $d = $h = $i = $s = 0;
        $invert = 0;
        if ($intervalValue < 0) {
            $invert = 1;
        }
        $intervalValue = abs($intervalValue);

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

        $formatString = "P{$y}Y{$M}M{$d}DT{$h}H{$i}M{$s}S";

        $interval = null;
        try {
            $interval = new DateInterval($formatString);
        } catch (Exception $e) {
        }
        $interval->invert = $invert;

        $target = $source->add($interval);

        if ($returnType === "timestamp" || $returnType === "ts") {
            return self::getTimestamp($target);
        }

        return $target;
    }

    /**
     * 对数字表示的timestamp进行格式化友好显示
     * @param int|null $time timestamp格式的时间
     * @param string $formatString 格式化字符串
     * @return string
     */
    public static function format(int $time = null, string $formatString = 'Y-m-d H:i:s'): string
    {
        $time = $time === null ? time() : (float)$time;
        //$time??= time();

        return self::getDateTime($time)->format($formatString);
    }

    /**
     * 获取某个指定的日期是星期几
     * @param int|null $timestamp 指定的日期（默认为当前日期）
     * @param string $format 返回星期几的格式
     *                            （默认（或者number,N,n）为数组0-7；
     *                            chinese,C,c:汉字 一，。。。日；
     *                            chinesefull,CF,cf:汉字全称 星期一。。。星期天）
     * @return string
     */
    public static function getWeekName(string $format = 'number', int $timestamp = null): string
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

        return match ($format) {
            "n" => $week,
            'c' => $result,
            default => '星期' . $result,
        };
    }

    /**
     * 获取日期中月份的中文叫法
     * @param int|null $month
     * @param string $postfixes 后缀信息
     * @return string
     */
    public static function getMonthChineseName(int $month = null, string $postfixes = ''): string
    {
        if ($month === null) {
            $month = date("n");
        }

        $result = match ($month) {
            1 => '一',
            2 => '二',
            3 => '三',
            4 => '四',
            5 => '五',
            6 => '六',
            7 => '七',
            8 => '八',
            9 => '九',
            10 => '十',
            11 => '十一',
            default => '十二',
        };

        return $result . $postfixes;
    }

    /**
     * 系统默认会读取php.ini中的date.timezone配置，如果没有配置，则会使用UTC时间。
     * 该方法可以改写系统默认使用的时区（尤其是在无法修改php.ini的环境下使用）。
     * @param string $timezone
     * @return void
     */
    public static function setTimeZone(string $timezone = 'Asia/Shanghai'): void
    {
        // 1. 校验时区有效性
        if (!in_array($timezone, timezone_identifiers_list(), false)) {
            // 2. 熔断机制：使用UTC兜底
            $timezone = "UTC";
        }

        // 3. 设置时区并添加日志
        date_default_timezone_set($timezone);
    }

    /**
     * 获取系统当前使用的时区字符串
     * @return string
     */
    public static function getTimeZoneString(): string
    {
        return date_default_timezone_get();
    }

    /**
     * 获取时区对象
     * @return DateTimeZone
     */
    public static function getTimeZoneObject(): DateTimeZone
    {
        $zoneName = self::getTimeZoneString();

        try {
            return new DateTimeZone($zoneName);
        } catch (Exception) {
            return new DateTimeZone("UTC");
        }
    }
}
