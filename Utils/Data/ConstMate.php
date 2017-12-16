<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/24
 * Time: 10:21
 */

namespace Hiland\Utils\Data;

/**
 * Class ConstMate
 * @package Vendor\Hiland\Utils\Data
 * 记录有业务常量的类型，继承此类。具体用法如下
 * // *
 * // * 常量命名有3部分（或4部分组成）
 * // *  第一部分为业务领域，比如订单、比如员工等
 * // *  第二部分为业务属性，比如订单配送状态、员工性别等
 * // *  第三部分为业务属性的取值，比如订单已经配送，订单已经取消等
 * // *  第四部分（如果有的话）为固定的字符串“TEXT”，表示对对应常量的文字解释
 * // *
 * // * 前两部分确定某几条常量为一个组。同一个组的常量有逻辑上的关联。
 * // * 逻辑上无联系的常量不要使用相同的第一二部分构成的前缀。
 * // * Class BizConst
 * // * @package Common\Model
 * //
 * //class BizConst extends ConstMate
 * //{
 * //    const ORDER_STATUS_CANCLED = -1;
 * //    const ORDER_STATUS_CANCLED_TEXT = "已取消";
 * //    const ORDER_STATUS_ORIGINAL = 0;
 * //    const ORDER_STATUS_ORIGINAL_TEXT = "未处理";
 * //    const ORDER_STATUS_SENDING = 1;
 * //    const ORDER_STATUS_SENDING_TEXT = "正在配送";
 * //    const ORDER_STATUS_DONE = 2;
 * //    const ORDER_STATUS_DONE_TEXT = "已完成";
 * //
 * //    const ORDER_PAYSTATUS_UNPAY = 0;
 * //    const ORDER_PAYSTATUS_UNPAY_TEXT = "未付款";
 * //    const ORDER_PAYSTATUS_PAID = 1;
 * //    const ORDER_PAYSTATUS_PAID_TEXT = "已付款";
 * //}
 */
class ConstMate
{
    /**
     * 根据给定的前缀和值获取常量的显示信息
     * @param $prefix
     * @param $value
     * @return mixed
     */
    public static function getConstText($prefix, $value)
    {
        $all = self::getConsts($prefix);
        $constKey = self::getConstName($prefix, $value);
        return $all[$constKey . "_TEXT"];
    }

    /**
     * 获取所有的常量
     * @param string $prefix 常量的前缀字符串
     * @param bool $withText 是否将_TEXT后缀的条目一起获取到
     * @return array
     */
    public static function getConsts($prefix = '', $withText = true)
    {
        $cacheKey = "Common-Model-BizConst-$prefix";
        if (APP_DEBUG) {
            return self::getConstsDetail($prefix, $withText);
        } else {
            $dataCached = S($cacheKey);
            if ($dataCached) {
                return $dataCached;
            } else {
                $dataCached = self::getConstsDetail($prefix, $withText);
                S($cacheKey, $dataCached);
                return $dataCached;
            }
        }
    }

    private static function getConstsDetail($prefix, $withText)
    {
        $className = get_called_class();
        $reflectionClass = ReflectionHelper::getReflectionClass($className);
        $all = $reflectionClass->getConstants();

        $result = array();
        if (empty($prefix)) {
            $result = $all;
        } else {
            foreach ($all as $key => $value) {
                if (StringHelper::isStartWith($key, $prefix)) {
                    if ($withText) {
                        $result[$key] = $value;
                    } else {
                        if (!StringHelper::isEndWith($key, "_TEXT")) {
                            $result[$key] = $value;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 根据给定的前缀和值获取常量的名称
     * @param $prefix
     * @param $value
     * @return bool|int|string
     */
    public static function getConstName($prefix, $value)
    {
        $all = self::getConsts($prefix);
        foreach ($all as $k => $v) {
            if ($v == $value) {
                return $k;
            }
        }

        return false;
    }

    /**
     * 获取某前缀构成的数组，Key为不带_TEXT常量的值，Value为带_TEXT常量的值
     * @param string $prefix
     * @param bool $keyValueModeForElement 组成数组的元素是否使用KeyValue模式，默认为true
     *      如果为true，返回的结果类似如下
     * array(7) {
     * [-1] => string(9) "已取消"
     * [0] => string(9) "未处理"
     * [1] => string(12) "正在配送"
     * [2] => string(9) "已完成"
     * [-2] => string(9) "退货中"
     * [-3] => string(9) "已退货"
     * [-4] => string(12) "申请退货"
     * }
     *      如果为false，返回的结果类似如下
     * array(7) {
     * [0] => array(2) {
     * ["value"] => int(-1)
     * ["text"] => string(9) "已取消"
     * }
     * [1] => array(2) {
     * ["value"] => int(0)
     * ["text"] => string(9) "未处理"
     * }
     * [2] => array(2) {
     * ["value"] => int(1)
     * ["text"] => string(12) "正在配送"
     * }
     * [3] => array(2) {
     * ["value"] => int(2)
     * ["text"] => string(9) "已完成"
     * }
     * [4] => array(2) {
     * ["value"] => int(-2)
     * ["text"] => string(9) "退货中"
     * }
     * [5] => array(2) {
     * ["value"] => int(-3)
     * ["text"] => string(9) "已退货"
     * }
     * [6] => array(2) {
     * ["value"] => int(-4)
     * ["text"] => string(12) "申请退货"
     * }
     * }
     *
     * @return array
     */
    public static function getConstArray($prefix, $keyValueModeForElement = true)
    {
        $all = self::getConsts($prefix, true);
        $result = array();
        foreach ($all as $key => $value) {
            if (!StringHelper::isEndWith($key, "_TEXT")) {
                if ($keyValueModeForElement) {
                    $result[$value] = $all[$key . "_TEXT"];
                } else {
                    $result[] = array(
                        "value" => $value,
                        "text" => $all[$key . "_TEXT"],
                    );
                }
            }
        }

        return $result;
    }
}