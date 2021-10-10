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
     * @param $htmlString
     * @return string
     */
    public static function cleanComment($htmlString)
    {
        return preg_replace('/<!--(.|\s)*?-->/', '', $htmlString);
    }
}