<?php

namespace Hiland\Utils\Data;

class StringHelper
{
    /**
     * @param $data
     * @param string $targetEncoding
     * @return false|string|string[]|null
     */
    public static function getEncodingContent($data, $targetEncoding = 'UTF-8')
    {
        $originalEncoding = self::getEncoding($data);

        $result = "";
        if ($originalEncoding) {
            $result = mb_convert_encoding($data, $targetEncoding, $originalEncoding);
        }

        return $result;
    }

    /**
     * 获取内容的编码
     * @param string $data
     * @return bool|string
     */
    public static function getEncoding($data = "")
    {
        return mb_detect_encoding($data);
    }

    /**
     * 获取文本文件的回车换行符
     * @return string
     */
    public static function getNewLineSymbol()
    {
        return PHP_EOL;
    }

    /**
     * 截取全角和半角（汉字和英文）混合的字符串以避免乱码
     *
     * @param string $original
     *            要截取的字符串
     * @param int $startPosition
     *            开始位置(第一个字符的位置为0)
     * @param int $length
     *            要截取的长度(超过总长度 按总长度计算)
     * @param string $charset
     * @return string
     * @author 小墨 244349067@qq.com
     */
    public static function subString($original, $startPosition, $length = 0, $charset = "utf-8")
    {
        $originalStringLength = strlen($original);

        if ($startPosition >= $originalStringLength) {
            return '';
        }

        if ($length > $originalStringLength - $startPosition) {
            $length = $originalStringLength - $startPosition;
        }

        if ($length == 0) {
            $length = $originalStringLength - $startPosition;
        }

        if (function_exists("mb_substr")) {
            $slice = mb_substr($original, $startPosition, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($original, $startPosition, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $original, $match);
            $slice = join("", array_slice($match[0], $startPosition, $length));
        }

        return $slice;
    }

    /**
     * @param string $padding 待测试的结尾字符
     * @param string $whole 全句
     * @return bool
     */
    public static function isEndWith($whole, $padding)
    {
        $paddingLength = strlen($padding);
        $fullLength = strlen($whole);
        $subString = substr($whole, $fullLength - $paddingLength);
        if ($subString == $padding) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $padding 待测试的开始字符
     * @param string $whole 全句
     * @return bool
     */
    public static function isStartWith($whole, $padding)
    {
        $before = self::getStringBeforeSeperator($whole, $padding);
        if ($before == '') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断一个字符串是否被包含在另外一个字符串内
     *
     * @param string $subString
     *            被查找的子字符串
     * @param string $whole
     *            查找的母体字符串
     * @return boolean
     */
    public static function isContains($whole, $subString)
    {
        $result = strstr($whole, $subString);

        if ($result === false) {
            return false;
        } else {
            return true;
        }
    }

    /**获取字符串长度
     * @param $strData
     * @param string $encoding
     * @return false|int
     */
    public static function getLength($strData, $encoding = "utf-8")
    {
        return mb_strlen($strData, $encoding);
    }

    /**包装php的字符串替换(将各个参数名称进一步明确化)
     * @param $whole
     * @param $old
     * @param $new
     * @return array|string|string[]
     */
    public static function replace($whole, $old, $new)
    {
        return str_replace($old, $new, $whole);
    }

    /**
     * 将一个字符串按照某个分隔符分隔成数组
     * @param $whole string 字符串全串
     * @param $delimiter string 分隔符
     * @return false|string[]
     */
    public static function explode($whole, $delimiter)
    {
        return explode($delimiter, $whole);
    }

    /**
     * 将一个数组的各个元素串联成一个字符串
     * @param $arrData
     * @param $delimiter
     * @return string
     */
    public static function implode($arrData, $delimiter="")
    {
        return  implode($delimiter, $arrData);
    }

    /**
     * 将一个字符串按照字符个数分组进行格式化
     * @param string $data string
     * @param string $formator string 字符串字符个数分组的格式，同一个分组内字符的个数用{}包围，各个分组之间可以自定义分隔符，例如
     *  '{4}-{2}-{2}'，或者'{4} {2} {2}'(中间用空格表示);
     * @return string
     */
    public static function grouping($data, $formator)
    {
        $content = '';
        $pattern = '/\{\d*\}/';
        $matches = null;
        $result = preg_match_all($pattern, $formator, $matches);
        if ($result) {
            foreach ($matches[0] as $matchedWithQuotation) {
                $matchedWithQuotationStartPosition = strpos($formator, $matchedWithQuotation);
                $matchedWithQuotationLength = strlen($matchedWithQuotation);
                $seperator = substr($formator, 0, $matchedWithQuotationStartPosition);
                $content .= $seperator;
                $seperatorLength = strlen($seperator);
                $formator = substr($formator, $matchedWithQuotationLength + $seperatorLength);

                $matchedNumber = StringHelper::getStringAfterSeperator($matchedWithQuotation, '{');
                $matchedNumber = StringHelper::getStringBeforeSeperator($matchedNumber, '}');
                $matchedNumber = (int)$matchedNumber;
                $dataLength = strlen($data);
                if ($dataLength >= $matchedNumber) {
                    $content .= substr($data, 0, $matchedNumber);
                    $data = substr($data, $matchedNumber);
                } else {
                    $content .= $data;
                    $data = '';
                }
            }
        }
        return $content;
    }


    /** 对带有占位符的字符串信息，进行格式化填充，形成完整的字符串。
     * 现在推荐直接使用 PHP系统自带的格式化方式,例如:"k的值为{$k}；v的值为{$v}"
     * @param $data string 带有占位符的字符串信息（占位符用{?}表示），例如 "i like this {?},do you known {?}"
     * @param $realValueList string[] 待填入的真实信息，用字符串数组表示，例如["qingdao","beijing"];
     *  或者使用用逗号分隔的各个独立的字符串表示,比如"qingdao","beijing"
     * @return string
     */
    public static function format($data, ...$realValueList)
    {
        $needle = "{?}";
        // 查找?位置
        $p = strpos($data, $needle);
        // 替换字符的数组下标
        $i = 0;

        if (ObjectHelper::getLength($realValueList) == 1 && ObjectHelper::getType($realValueList[0]) == ObjectTypes::ARRAYS) {
            $realValueList = $realValueList[0];
        }

        while ($p !== false) {
            $data = substr_replace($data, $realValueList[$i++], $p, 3);
            // 查找下一个?位置  没有时会退出循环
            $p = strpos($data, $needle, ++$p);
        }

        return $data;
    }
    
    
     /**
     * 获取字符串分隔符前面的内容
     *
     * @param string $whole
     * @param string $seperator
     * @return string
     */
    public static function getStringBeforeSeperator($whole, $seperator)
    {
        if (self::isContains($whole, $seperator)) {
            $array = explode($seperator, $whole);
            return $array[0];
        } else {
            return $whole;
        }
    }

    /**
     * 获取字符串分隔符后面的内容
     *
     * @param string $whole
     * @param string $seperator
     * @return string
     */
    public static function getStringAfterSeperator($whole, $seperator)
    {
        if (self::isContains($whole, $seperator)) {
            $array = explode($seperator, $whole);
            return $array[1];
        } else {
            return $whole;
        }
    }

    /**
     * 将字符串中第一个单词的首字母大写
     * @param $data
     * @return string
     */
    public static function upperStringFirstChar($data)
    {
        return ucfirst($data);
    }

    /**
     * 将字符串中每一个单词的首字母大写
     * @param $data
     * @return string
     */
    public static function upperWordsFirstChar($data)
    {
        return ucwords($data);
    }

    /**
     * 将字符串中每一个字母都转成大写
     * @param $data
     * @return string
     */
    public static function upper($data)
    {
        return mb_strtoupper($data);
    }

    /**
     * 将字符串中每一个字母都转成小写
     * @param $data
     * @return string
     */
    public static function lower($data)
    {
        return mb_strtolower($data);
    }
}