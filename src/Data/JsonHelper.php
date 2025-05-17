<?php
/**
 * @file   : JsonHelper.php
 * @time   : 11:32
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Data;

/**
 * 转换安全的 Json 辅助类
 */
class JsonHelper
{
    /**
     * 将 object/array 实体转换成 json 字符串
     * @param object|array $entity
     * @param bool $retainChinese 是否保留中文等非 ASCII 字符，不转换成 unicode 编码。默认 true。
     * @return string|bool (如果转换失败，返回 false)
     */
    public static function entity2String(object|array $entity, bool $retainChinese = true): string|bool
    {
        try {
            $flags = JSON_THROW_ON_ERROR;
            if ($retainChinese) {
                $flags |= JSON_UNESCAPED_UNICODE;
            }
            return json_encode($entity, $flags);
        } /** @noinspection all */
        catch (\JsonException $e) {
            return false;
        }
    }


    /**
     * 将 json 字符串转换成 array 数组
     * @param string $jsonString
     * @return array|null (如果转换失败，返回 null)
     */
    public static function string2Array(string $jsonString): array|null
    {
        try {
            return json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
        } /** @noinspection all */
        catch (\JsonException $e) {
            return null;
        }
    }

    /**
     * 将 json 字符串转换成 object 对象
     * @param string $jsonString
     * @return object|null (如果转换失败，返回 null)
     */
    public static function string2Object(string $jsonString): object|null
    {
        try {
            return json_decode($jsonString, false, 512, JSON_THROW_ON_ERROR);
        } /** @noinspection all */
        catch (\JsonException $e) {
            return null;
        }
    }
}
