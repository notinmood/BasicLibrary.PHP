<?php
/**
 * @file   : DatabaseHelper.php
 * @time   : 14:18
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\ObjectTypes;
use Hiland\Utils\Data\StringHelper;

class DatabaseHelper
{
    /**
     * 构建Insert语句
     * @param string $table_name 待操作的数据库表名称
     * @param        $entity_dict_or_list
     * @return string
     */
    public static function buildInsertClause($table_name, $entity_dict_or_list)
    {
        $level = ArrayHelper::getLevel($entity_dict_or_list);
        $isIndexArray = ArrayHelper::isIndexArray($entity_dict_or_list);

        /**
         * 判断是实体还是实体集合
         */
        if ($level == 2 && $isIndexArray) {
            $result = "";
            foreach ($entity_dict_or_list as $item) {
                $single_sql = self::__buildInsertClause($table_name, $item);
                $single_sql = StringHelper::removeTail($single_sql, ";");

                if ($result) {
                    $values_sql = StringHelper::getStringAfterSeparator($single_sql, "VALUES");
                    $result .= "," . $values_sql;
                } else {
                    $result = $single_sql;
                }
            }

            return $result . ";";
        } else {
            return self::__buildInsertClause();
        }
    }

    private static function __buildInsertClause($table_name, $entity)
    {
        $keys = "";
        $values = "";

        foreach ($entity as $k => $v) {
            $keys .= "`{$k}`,";
            $values .= self::wrapSqlValue($v) . ",";
        }

        if (StringHelper::isEndWith($keys, ",")) {
            $keys = StringHelper::removeTail($keys, 1);
            $values = StringHelper::removeTail($values, 1);
        }

        return "INSERT INTO `{$table_name}` ({$keys}) VALUES ({$values});";
    }

    /**
     * @param $data
     * @return string
     */
    public static function wrapSqlValue($data)
    {
        $_type = ObjectHelper::getTypeName($data);
        if ($_type == ObjectTypes::STRING || $_type == ObjectTypes::DATETIME) {
            return "'{$data}'";
        } else {
            return "{$data}";
        }
    }
}