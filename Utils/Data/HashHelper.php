<?php

namespace Hiland\Utils\Data;

class HashHelper
{
    /**
     * 计算给定字符串的哈希值（转换为数字的值），或者取模的结果（如果设定$maxremainder的话）
     *
     * @param string $dataString
     *            待进行hash计算的字符串
     * @param int $maxRemainder
     *            是否对哈希结果进行取模的模数值，缺省为0不进行取模
     * @param bool $fix
     *            当余数为0的时候，是否修正余数值为模数值（$maxremainder），缺省不修正
     * @return mixed
     */
    public static function getDigital($dataString, $maxRemainder = 0, $fix = false)
    {
        $data = hash('sha256', $dataString);

        $data = base_convert($data, 16, 10);

        if ($maxRemainder > 0) {
            $data = substr($data, 0, 9);
            $data = intval($data) % intval($maxRemainder);
            if ($data == 0 && $fix == true) {
                $data = $maxRemainder;
            }
        }

        return $data;
    }
}