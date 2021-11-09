<?php

namespace Hiland\Biz\Stock;

use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\StringHelper;

class StockHelper
{

    /**
     * @param        $stockCode
     * @param string $prefix  前缀信息(带[]或者不带[]).
     *                        []外的信息,固定不变;
     *                        []内的信息是会根据具体情况改变，其中
     *                        - 如果取SEN(StockExchangeName股票交易所名称),将会替换成SH(上海)或者SZ(深圳)
     *                        - 如果取sen(StockExchangeName股票交易所名称),将会替换成sh(上海)或者sz(深圳)
     * @param string $postfix 后缀信息.(格式同$prefix)
     * @return mixed
     */
    public static function formatStockCode($stockCode, $prefix = "[SEN]", $postfix = "")
    {
        $stockCode = self::getStandardStockCode($stockCode);

        if (!$stockCode) {
            return "";
        } else {
            $standardCode = $stockCode;
            $bizCode = "{$prefix}{$standardCode}{$postfix}";
            $stockExchangeNameUpper = self::getStockExchangeName($standardCode);
            $stockExchangeNameLower = StringHelper::lower($stockExchangeNameUpper);

            $target = $bizCode;

            /**
             * 明确指定的都转换为小写的交易所名称
             */
            $target = preg_replace("/\[sen\]/", $stockExchangeNameLower, $target);

            /**
             * 其他非明确指定的都换成大写交易所的名称
             */

            /**
             * 添加其他替换过滤条件
             */
            return preg_replace("/\[SEN\]/i", $stockExchangeNameUpper, $target);
        }
    }

    /**
     * 获取交易所名称
     * @param $stockCode 股票代码
     * @return string
     */
    public static function getStockExchangeName($stockCode)
    {
        $stockCode = self::getStandardStockCode($stockCode);
        if (!$stockCode) {
            return "";
        }

        $standardCodeFirstChar = StringHelper::subString($stockCode, 0, 1);
        switch ($standardCodeFirstChar) {
            case "6":
            case "7":
            case "9":
                $stockExchangeName = "SH";
                break;
            default:
                $stockExchangeName = "SZ";
        }

        return $stockExchangeName;
    }

    /**
     * 获取6位数的标准股票代码
     * @param $stockCode
     * @return mixed|string
     */
    public static function getStandardStockCode($stockCode)
    {
        preg_match("/[0|3|6|7|9]\d{5}/", $stockCode, $matches);

        if (ObjectHelper::isEmpty($matches)) {
            return "";
        } else {
            return $matches[0];
        }
    }
}