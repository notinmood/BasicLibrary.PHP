<?php
/**
 * Created by PhpStorm.
 * User: devel
 * Date: 2016/3/11 0011
 * Time: 7:05
 */

namespace Hiland\Utils\Web;


class PageHelper
{
    public static function renderHeader($coding = 'utf-8')
    {
        echo "<head>";
        echo self::buildCoding($coding);
        echo "</head>";
    }

    private static function buildCoding($coding = 'utf-8')
    {
        $content = '<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=' . $coding . '"> <meta charset="' . $coding . '">';
        return $content;
    }

    /**
     * 在页面的header标签内设置字符编码（页面html代码可见此设置的文本）
     * @param string $coding
     */
    public static function renderCoding($coding = 'utf-8')
    {
        echo self::buildCoding($coding);
    }

    /**
     * 在页面响应流的header部分设置字符编码（页面html代码不可见此设置的文本）
     * @param string $coding
     */
    public static function setCoding($coding = 'utf-8')
    {
        header('Content-Type: text/html; charset=' . $coding);
    }
}