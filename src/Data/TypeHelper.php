<?php
/**
 * @file   : TypeHelper.php
 * @time   : 14:19
 * @date   : 2025/3/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

class TypeHelper
{
    /**
     * 转换数据类型
     * @param $value mixed 要转换的数据
     * @param string $targetType 目标数据类型的字符串名字（建议使用ObjectTypes::XXX表示）
     * @return array|bool|float|int|mixed|object|stdClass|string
     */
    public static function convertType(mixed $value, string $targetType): mixed
    {
        return Convertor::changeType($value, $targetType);
    }


}