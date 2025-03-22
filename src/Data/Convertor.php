<?php
/**
 * @file   : Convertor.php
 * @time   : 14:01
 * @date   : 2025/3/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\Data;

class Convertor
{
    /**
     * 转换数据类型
     * @param $value mixed 要转换的数据
     * @param string $targetType 目标数据类型的字符串名字（建议使用ObjectTypes::XXX表示）
     * @return array|bool|float|int|mixed|object|stdClass|string
     * @throws \JsonException
     */
    public static function changeType(mixed $value, string $targetType): mixed
    {
        switch (strtolower($targetType)) {
            case 'integer':
            case 'int':
                return (int)$value;
            case 'float':
            case 'double':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'boolean':
            case 'bool':
                return (bool)$value;
            case 'array':
                if (is_string($value)) {
                    // 如果值是 JSON 格式的字符串，将其转换为数组
                    try {
                        return json_decode($value, true, 512, JSON_THROW_ON_ERROR) ?: [];
                    } catch (JsonException $e) {
                        return [];
                    }
                }
                return (array)$value;
            case 'object':
                if (is_string($value)) {
                    // 如果值是 JSON 格式的字符串，将其转换为对象
                    try {
                        return json_decode($value, false, 512, JSON_THROW_ON_ERROR) ?: new stdClass();
                    } catch (JsonException $e) {
                        return new stdClass();
                    }
                }
                return (object)$value;
            default:
                throw new InvalidArgumentException("不支持的目标类型: {$targetType}");
        }
    }


}