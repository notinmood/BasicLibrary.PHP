<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/8
 * Time: 19:23
 */

namespace Hiland\Utils\Data;


class HtmlHelper
{
    /**
     *清除html中的注释
     * @param string $htmlString
     * @return string
     */
    public static function cleanComment(string $htmlString): string
    {
        return preg_replace('/<!--(.|\s)*?-->/', '', $htmlString);
    }
}