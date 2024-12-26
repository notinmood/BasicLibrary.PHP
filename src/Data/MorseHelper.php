<?php


namespace Hiland\Data;

/**
 * Class MorseHelper 摩尔斯电报码
 * @package Hiland\Data
 * https://gitee.com/fubinwei/morse-php/blob/master/morse.php#
 * 例子：
 * $data= MorseHelper::encode("这是一个伟大的时代，hello！");
 * dump($data);
 * $data= MorseHelper::decode($data);
 * dump($data);
 */
class MorseHelper
{
    //加密代码
    private static array $option   = array(
        'space' => '/',
        'short' => '.',
        'long'  => '-',
    );
    private static array $standard = array(
        /* Letters                               */
        'A'  => '01',      /* A                   */
        'B'  => '1000',    /* B                   */
        'C'  => '1010',    /* C                   */
        'D'  => '100',     /* D                   */
        'E'  => '0',       /* E                   */
        'F'  => '0010',    /* F                   */
        'G'  => '110',     /* G                   */
        'H'  => '0000',    /* H                   */
        'I'  => '00',      /* I                   */
        'J'  => '0111',    /* J                   */
        'K'  => '101',     /* K                   */
        'L'  => '0100',    /* L                   */
        'M'  => '11',      /* M                   */
        'N'  => '10',      /* N                   */
        'O'  => '111',     /* O                   */
        'P'  => '0110',    /* P                   */
        'Q'  => '1101',    /* Q                   */
        'R'  => '010',     /* R                   */
        'S'  => '000',     /* S                   */
        'T'  => '1',       /* T                   */
        'U'  => '001',     /* U                   */
        'V'  => '0001',    /* V                   */
        'W'  => '011',     /* W                   */
        'X'  => '1001',    /* X                   */
        'Y'  => '1011',    /* Y                   */
        'Z'  => '1100',    /* Z                   */
        /* Numbers                               */
        '0'  => '11111',   /* 0                   */
        '1'  => '01111',   /* 1                   */
        '2'  => '00111',   /* 2                   */
        '3'  => '00011',   /* 3                   */
        '4'  => '00001',   /* 4                   */
        '5'  => '00000',   /* 5                   */
        '6'  => '10000',   /* 6                   */
        '7'  => '11000',   /* 7                   */
        '8'  => '11100',   /* 8                   */
        '9'  => '11110',   /* 9                   */
        /* Punctuation                           */
        '.'  => '010101',  /* Full stop           */
        ','  => '110011',  /* Comma               */
        '?'  => '001100',  /* Question mark       */
        '\'' => '011110', /* Apostrophe          */
        '!'  => '101011',  /* Exclamation mark    */
        '/'  => '10010',   /* Slash               */
        '('  => '10110',   /* Left parenthesis    */
        ')'  => '101101',  /* Right parenthesis   */
        '&'  => '01000',   /* Ampersand           */
        '=>' => '111000', /* Colon               */
        ';'  => '101010',  /* Semicolon           */
        '='  => '10001',   /* Equal sign          */
        '+'  => '01010',   /* Plus sign           */
        '-'  => '100001',  /* Hyphen1minus        */
        '_'  => '001101',  /* Low line            */
        '"'  => '010010',  /* Quotation mark      */
        '$'  => '0001001', /* Dollar sign         */
        '@'  => '011010',  /* At sign             */
    );

    private static $standardReverse = null;

    /**
     * 加密摩斯电码
     * @param string $stringData
     * @param null   $option
     * @return string
     */
    public static function encode(string $stringData, $option = null): string
    {
        $option = self::defaultOption($option); // 默认参数
        $morse  = [];                           // 最终的 morse 结果
        // 删除空格，转大写，分割为数组
        $stringData = self::mbStrSplit(strtoupper(str_replace(' ', '', $stringData)));
        foreach ($stringData as $key => $ch) {
            $r = @self::$standard[$ch];
            if (!$r && $r != "0") {              //"0"是字母E，因此要排除
                $r = self::unicodeHexMorse($ch); // 找不到，说明是非标准的字符，使用 unicode。
            }
            $morse[] = str_replace('1', $option[2], str_replace('0', $option[1], $r));
        }
        return join($option[0], $morse);
    }

    //按 utf 分割字符串

    /**
     * @param array|null $option
     * @return array
     */
    private static function defaultOption(array $option = null): array
    {
        $option = $option || [];
        return [
            $option['space'] ?? self::$option['space'],
            $option['short'] ?? self::$option['short'],
            $option['long'] ?? self::$option['long'],
        ];
    }

    private static function mbStrSplit($stringData): array
    {
        $len    = 1;
        $start  = 0;
        $length = mb_strlen($stringData);
        $array  = [];
        while ($length) {
            $array[]    = mb_substr($stringData, $start, $len, "utf8");
            $stringData = mb_substr($stringData, $len, $length, "utf8");
            $length     = mb_strlen($stringData);
        }
        return $array;
    }

    public static function unicodeHexMorse($ch): string
    {
        $r      = [];
        $length = mb_strlen($ch, 'UTF8');
        for ($i = 0; $i < $length; $i++) {
            $r[$i] = substr(('00' . dechex(self::charCodeAt($ch, $i))), -4);
        }
        $r = join('', $r);
        return base_convert(hexdec($r), 10, 2);
    }

    //摩斯码转文本

    private static function charCodeAt($str, $index)
    {
        $char = mb_substr($str, $index, 1, 'UTF-8');
        if (mb_check_encoding($char, 'UTF-8')) {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        } else {
            return null;
        }
    }

    //unicode转二进制

    /**
     * 解密摩斯电码
     * @param string $morseStringData
     * @param null   $option
     * @return string
     */
    public static function decode(string $morseStringData, $option = null): string
    {
        if (self::$standardReverse === null) {
            foreach (self::$standard as $key => $value) {
                self::$standardReverse[$value] = $key;
            }
        }

        $option          = self::defaultOption($option);
        $msg             = [];
        $morseStringData = explode($option[0], $morseStringData); // 分割为数组
        foreach ($morseStringData as $key => $mor) {
            $mor = str_replace(' ', '', $mor);
            $mor = str_replace($option[2], '1', str_replace($option[1], '0', $mor));

            $r = @self::$standardReverse[$mor];
            if (!$r) {
                /**
                 * 找不到，说明是非标准字符的 morse，使用 unicode 解析方式。
                 */
                $r = self::morseHexUnicode($mor);
            }
            $msg[] = $r;
        }
        return join('', $msg);
    }

    private static function morseHexUnicode($mor): string
    {
        $mor = bindec($mor);
        if (!$mor) {
            return '';
        } else {
            $mor = dechex($mor);
        }
        return StringHelper::convertUnicodeToUTF8($mor);
    }
}
