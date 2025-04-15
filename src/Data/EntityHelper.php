<?php
/**
 * @file   : EntityHelper.php
 * @time   : 17:25
 * @date   : 2025/4/15
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\Data;

/**
 * 实体辅助类
 */
class EntityHelper
{
    /**
     * 格式化日期字段
     * @param $entity array|mixed 实体数据(可以是普通的实体数组，或者是ORM实体对象)
     * @param $format string 时间格式：如果为空，则默认为Y-m-d
     * @param ...$fields string 字段名
     * @return array|mixed 格式化后的实体数据
     */
    public static function FormatDate(mixed $entity, string $format = '', ...$fields): mixed
    {
        if (empty($format)) {
            $format = 'Y-m-d';
        }

        foreach ($fields as $field) {
            if ($entity[$field]) {
                //如果是字符串，先转换为时间戳
                if (is_string($entity[$field])) {
                    $entity[$field] = strtotime($entity[$field]);
                }

                //将时间戳格式化为指定格式的时间字符串
                if (is_int($entity[$field])) {
                    $entity[$field] = date($format, $entity[$field]);
                }
            } else {
                $entity[$field] = '';
            }
        }

        return $entity;
    }
}