<?php

namespace Hiland\Utils\Finance;

/**
 * 人民币操作类
 * @author 山东解大劦
 */
class RMB
{
    /**
     * 数字转换为中文
     * @param string|integer|float $num         目标数字
     * @param bool                 $financeMode 模式[true:金额（默认）,false:普通数字表示]
     * @param bool                 $simpleMode  使用简体小写还是繁体大写（默认小写）
     * @return string
     * @example
     *                                          $num = ’0123648867.789′;
     *                                          echo $num,’
     *                                          ‘;
     *                                          //普通数字的汉字表示
     *                                          echo ‘普通:’,displayChineseValue($num,false),”;
     *                                          echo ‘
     *                                          ‘;
     *                                          //金额汉字表示
     *                                          echo ‘金额(简体):’,displayChineseValue($num,true),”;
     *                                          echo ‘
     *                                          ‘;
     *                                          echo ‘金额(繁体):’,displayChineseValue($num,true,false);
     */
    public static function displayChineseValue($num, bool $financeMode = true, bool $simpleMode = true): string
    {
        if (!is_numeric($num)) return '含有非数字非小数点字符！';
        $char   = $simpleMode ? array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九')
            : array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        $unit   = $simpleMode ? array('', '十', '百', '千', '', '万', '亿', '兆')
            : array('', '拾', '佰', '仟', '', '萬', '億', '兆');
        $result = $financeMode ? '元' : '点';

        //小数部分
        if (strpos($num, '.')) {
            list($num, $dec) = explode('.', $num);
            $dec = strval(round($dec, 2));
            if ($financeMode) {
                $result .= "{$char[$dec['0']]}角{$char[$dec['1']]}分";
            } else {
                for ($i = 0, $c = strlen($dec); $i < $c; $i++) {
                    $result .= $char[$dec[$i]];
                }
            }
        }

        //整数部分
        $str = $financeMode ? strrev(intval($num)) : strrev($num);
        $out = null;
        for ($i = 0, $c = strlen($str); $i < $c; $i++) {
            $out[$i] = $char[$str[$i]];
            if ($financeMode) {
                $out[$i] .= $str[$i] != '0' ? $unit[$i % 4] : '';
                if ($i > 1 and $str[$i] + $str[$i - 1] == 0) {
                    $out[$i] = '';
                }
                if ($i % 4 == 0) {
                    $out[$i] .= $unit[4 + floor($i / 4)];
                }
            }
        }

        return join('', array_reverse($out)) . $result;
    }
}
