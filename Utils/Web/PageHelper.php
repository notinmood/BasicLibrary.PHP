<?php
/**
 * Created by PhpStorm.
 * User: devel
 * Date: 2016/3/11 0011
 * Time: 7:05
 */

namespace Hiland\Utils\Web;

/**
 * 
 */
class PageHelper
{
    public static function renderHeader($coding = 'utf-8')
    {
        echo "<head>";
        echo self::buildEncoding($coding);
        echo "</head>";
    }

    private static function buildEncoding($coding = 'utf-8'): string
    {
        return '<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=' . $coding . '"> <meta charset="' . $coding . '">';
    }

    /**
     * 在页面的header标签内设置字符编码（页面html代码可见此设置的文本）
     * @param string $coding
     */
    public static function renderEncoding(string $coding = 'utf-8')
    {
        echo self::buildEncoding($coding);
    }

    /**
     * 在页面响应流的header部分设置字符编码（页面html代码不可见此设置的文本）
     * @param string $coding
     */
    public static function setEncoding(string $coding = 'utf-8')
    {
        header('Content-Type: text/html; charset=' . $coding);
    }
}
