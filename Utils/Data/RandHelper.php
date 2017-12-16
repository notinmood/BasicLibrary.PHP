<?php
namespace Hiland\Utils\Data;

class RandHelper
{

    /**
     * 生成随机字符串
     *
     * @param int $length
     *            字符串长度
     * @param string $format
     *            可以出现在字符串中的字符类别，取值分别为
     *            ALL 包括大写小写字符、数字、特殊字符
     *            LETTER 大写小写字符
     *            NUMBER 数字
     *            SEPCIAL 特殊字符
     *            [任意值] 大写小写字符、数字（不包含特殊字符）
     * @return string
     */
    public static function rand($length = 8, $format = 'ALL')
    {
        $result = '';

        $upperLetter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerLetter = 'abcdefghijklmnopqrstuvwxyz';
        $digital = '0123456789';
        $sepcial = '~!@#$%^&*()_+|}{<>?-=\/,.';

        switch ($format) {
            case 'ALL':
                $chars = $upperLetter . $lowerLetter . $digital . $sepcial;
                break;
            case 'LETTER':
                $chars = $upperLetter . $lowerLetter;
                break;
            case 'NUMBER':
                $chars = $digital;
                break;
            case 'SEPCIAL':
                $chars = $sepcial;
                break;
            default:
                $chars = $upperLetter . $lowerLetter . $digital;
                break;
        }

        $charCount = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $position = mt_rand(0, $charCount);
            $result .= $chars[$position];
        }

        return $result;
    }
}

?>