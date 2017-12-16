<?php
/**
 * Created by PhpStorm.
 * User: devel
 * Date: 2016/3/9 0009
 * Time: 10:27
 */

namespace Hiland\Utils\Data;


class RegexHelper
{
    const GUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

    const MOBILE = '/^1[3|4|5|7|8][0-9]{9}$/';

    const EMAIL = '/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i';

    const URL = '#(http|https|ftp|ftps)://([w-]+.)+[w-]+(/[w-./?%&=]*)?#i';

    const IP = '/^((25[0-5]|2[0-4]\d|[01]?\d\d?)($|(?!\.$)\.)){4}$/';
}