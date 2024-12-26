<?php
/**
 * @file   : DatabaseHelper.php
 * @time   : 14:18
 * @date   : 2021/12/31
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\DataModel;

use Hiland\Data\ArrayHelper;
use Hiland\Data\ObjectHelper;
use Hiland\Data\ObjectTypes;
use Hiland\Data\StringHelper;

/**
 *
 */
class DatabaseHelper
{
    /**
     * 构建Insert语句
     * @param string $tableName 待操作的数据库表名称
     * @param array  $entityDictOrList
     * @return string
     */
    public static function buildInsertClause(string $tableName, array $entityDictOrList): string
    {
        $level        = ArrayHelper::getLevel($entityDictOrList);
        $isIndexArray = ArrayHelper::isIndexArray($entityDictOrList);

        /**
         * 判断是实体还是实体集合
         */
        if ($level == 2 && $isIndexArray) {
            $result = "";
            foreach ($entityDictOrList as $item) {
                $single_sql = self::__buildInsertClause($tableName, $item);
                $single_sql = StringHelper::removeTail($single_sql, ";");

                if ($result) {
                    $values_sql = StringHelper::getStringAfterSeparator($single_sql, "VALUES");
                    $result     .= "," . $values_sql;
                } else {
                    $result = $single_sql;
                }
            }

            return $result . ";";
        } else {
            return self::__buildInsertClause($tableName, $entityDictOrList);
        }
    }

    private static function __buildInsertClause($tableName, $entityArray): string
    {
        $keys   = "";
        $values = "";

        foreach ($entityArray as $k => $v) {
            $keys   .= "`$k`,";
            $values .= self::wrapSqlValue($v) . ",";
        }

        if (StringHelper::isEndWith($keys, ",")) {
            $keys   = StringHelper::removeTail($keys, 1);
            $values = StringHelper::removeTail($values, 1);
        }

        return "INSERT INTO `$tableName` ($keys) VALUES ($values);";
    }

    /**
     * @param $data
     * @return string
     */
    public static function wrapSqlValue($data): string
    {
        $_type = ObjectHelper::getTypeName($data);
        if ($_type == ObjectTypes::STRING || $_type == ObjectTypes::DATETIME) {
            return "'$data'";
        } else {
            return "$data";
        }
    }
}
