<?php

namespace Hiland\Data;

class ColorHelper
{
    /**
     * 将 16进制的颜色值转变为 RGB 数组
     * @param string $dexColor 十六进制颜色,例如 #ffffff
     * @return array 包含了 RGB 3个元素的一维数组
     */
    public static function Hex2RGB(string $dexColor): array
    {
        $startPosition = 0;
        if (substr($dexColor, 0, 1) == "#") {
            $startPosition = 1;
        }

        $result = array();
        for ($ii = $startPosition; $ii < strlen($dexColor); $ii++) {
            $result[] = hexdec(substr($dexColor, $ii, 2));
            $ii++;
        }

        return $result;
    }

    /**
     * 将RGB颜色转换为16进制表示的颜色
     * @param int $R
     * @param int $G
     * @param int $B
     * @return string
     */
    public static function RGB2Hex(int $R, int $G, int $B): string
    {
        $dexColor = '#';
        $dexColor .= str_pad(dechex((int)$R), 2, '0', STR_PAD_LEFT);
        $dexColor .= str_pad(dechex((int)$G), 2, '0', STR_PAD_LEFT);
        $dexColor .= str_pad(dechex((int)$B), 2, '0', STR_PAD_LEFT);

        return $dexColor;
    }
}
