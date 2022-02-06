<?php

namespace Hiland\Utils\Data;

use Hiland\Utils\DataValue\SystemEnum;

class RandHelper
{
    /**
     * 生成随机字符串
     * @param int    $length
     *            字符串长度
     * @param string $category
     *            可以出现在字符串中的字符类别，取值分别为
     *            ALL 包括大写小写字符、数字、特殊字符
     *            LETTER 大写小写字符
     *            NUMBER 数字
     *            SPECIAL 特殊字符
     *            [任意值] 大写小写字符、数字（不包含特殊字符）
     * @return string
     */
    public static function get(int $length = 8, string $category = SystemEnum::RandCategory_ALL): string
    {
        $result = '';

        $upperLetter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerLetter = 'abcdefghijklmnopqrstuvwxyz';
        $digital = '0123456789';
        $special = '~!@#$%^&*()_+|}{<>?-=\/,.';

        switch ($category) {
            case SystemEnum::RandCategory_ALL:
                $chars = $upperLetter . $lowerLetter . $digital . $special;
                break;
            case SystemEnum::RandCategory_LETTER:
                $chars = $upperLetter . $lowerLetter;
                break;
            case SystemEnum::RandCategory_NUMBER:
                $chars = $digital;
                break;
            case SystemEnum::RandCategory_SPECIAL:
                $chars = $special;
                break;
            default:
                $chars = $upperLetter . $lowerLetter . $digital;
                break;
        }

        $charCount = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $position = mt_rand(0, $charCount);
            $result .= $chars[$position];
        }

        return $result;
    }
}