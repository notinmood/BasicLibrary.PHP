<?php
namespace Hiland\Utils\Data;

class DateHelper
{

    /**
     * 获取从1970年1月1日以来总共的毫秒数
     *
     * @return float
     */
    public static function getTotalMilliSeconds()
    {
        list ($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 获取当前时间的毫秒数信息
     *
     * @return float
     */
    public static function getCurrentMilliSecond()
    {
        list ($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1)) * 1000);
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
     */
    public static function addInterval($originalTimestamp = null, $intervalType = "d", $intervalValue = 1)
    {
        if (empty($originalTimestamp)) {
            $originalTimestamp = time();
        }
        $datetimearray = getdate($originalTimestamp);
        $hours = $datetimearray["hours"];
        $minutes = $datetimearray["minutes"];
        $seconds = $datetimearray["seconds"];
        $month = $datetimearray["mon"];
        $day = $datetimearray["mday"];
        $year = $datetimearray["year"];
        switch ($intervalType) {
            case "y":
            case "Y":
                $year += $intervalValue;
                break;
            case "q":
            case "Q":
                $month += ($intervalValue * 3);
                break;
            case "M":
                $month += $intervalValue;
                break;
            case "d":
            case "D":
                $day += $intervalValue;
                break;
            case "w":
            case "W":
                $day += ($intervalValue * 7);
                break;
            case "h":
            case "H":
                $hours += $intervalValue;
                break;
            case "m":
            case "i":
            case "I":
                $minutes += $intervalValue;
                break;
            case "s":
            case "S":
                $seconds += $intervalValue;
                break;
        }
        $timestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
        return $timestamp;
    }

    /**
     * 对数字表示的timestamp进行格式化友好显示
     * @param int $time timestamp格式的时间
     * @param string $formatString 格式化字符串
     * @return string
     */
    public static function format($time = NULL, $formatString = 'Y-m-d H:i:s')
    {
        $time = $time === NULL ? time() : intval($time);

        return date($formatString, $time);
    }

    /**
     * 获取一个指定时间点的timestamp
     * @param string $date 指定的时间点 ，可以是“201603161312”格式，也可以是“2016-03-16 13:12:25”
     * @return int
     */
    public static function getTimestamp($date)
    {
        if (StringHelper::isContains($date, ' ')) {
            $datePart = StringHelper::getSeperatorBeforeString($date, ' ');
            $timePart = StringHelper::getSeperatorAfterString($date, ' ');
        } else {
            $datePart = $date;
            $timePart = '';
        }

        if (!StringHelper::isContains($datePart, '-')) {
            $temp = $datePart;
            $datePart = substr($temp, 0, 8);
            $timePart = substr($temp, 8);
            if (strlen($timePart) < 6) {
                $timePart = str_pad($timePart, 6, '0', STR_PAD_RIGHT);
            }
            $datePart = StringHelper::format($datePart, '{4}-{2}-{2}');
            $timePart = StringHelper::format($timePart, '{2}:{2}:{2}');
        }

        $date = "$datePart $timePart";
        return strtotime($date);
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
    public static function getMonthChineseName($month=null,$postfixstring=''){
        if($month==null){
            $month= date("n");
        }

        switch ($month){
            case 1:
                $result= '一';
                break;
            case 2:
                $result= '二';
                break;
            case 3:
                $result= '三';
                break;
            case 4:
                $result= '四';
                break;
            case 5:
                $result= '五';
                break;
            case 6:
                $result= '六';
                break;
            case 7:
                $result= '七';
                break;
            case 8:
                $result= '八';
                break;
            case 9:
                $result= '九';
                break;
            case 10:
                $result= '十';
                break;
            case 11:
                $result= '十一';
                break;
            case 12:
                $result= '十二';
                break;
        }

        return $result.$postfixstring;
    }

}

?>