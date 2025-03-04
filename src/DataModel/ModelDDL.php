<?php
/**
 * @file   : ModelDDL.php
 * @time   : 9:43
 * @date   : 2021/12/31
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\DataModel;

use Exception;
use Hiland\Data\ArrayHelper;
use Hiland\Data\ObjectHelper;
use Hiland\Data\StringHelper;


/**
 * 内部方法之所以不设置静态，是为了扩展多种的数据库类型
 * (目前仅仅支持 Mysql 数据库类型)
 */
class ModelDDL
{
    /**
     * 复制数据表
     * @param string $originalTableName
     * @param string $newTableName
     * @param int    $includeDataRowCount 复制数据的行数(缺省0，表示不复制数据行;-1表示复制所有行)
     * @param bool   $dropTableIfExist
     * @return void
     */
    public function duplicateTable(string $originalTableName, string $newTableName = "", int $includeDataRowCount = 0, bool $dropTableIfExist = True)
    {
        if (ObjectHelper::isEmpty($newTableName)) {
            $newTableName = $originalTableName . "__duplication__";
        }

        if ($dropTableIfExist) {
            $this->dropTable($newTableName);
        }

        $create_sql = $this->getTableDefinition($originalTableName);

        # TODO:需要使用正则表达式替换
        $create_sql = StringHelper::replace($create_sql, $originalTableName, $newTableName);

        $mate = DatabaseClient::getMate($originalTableName);
        $mate->directlyExecute($create_sql);

        $insert_sql = $this->getContentSql($originalTableName, $includeDataRowCount);
        $insert_sql = StringHelper::replace($insert_sql, $originalTableName, $newTableName);
        if ($insert_sql) {
            $mate->directlyExecute($insert_sql);
        }
    }

    /**
     * 删除数据表
     * @param string $tableName         数据表名称
     * @param bool   $bothStructAndData 是否同时删除表结构和数据(true(缺省值)全部删除;false仅删除数据，保留表结构)
     * @return void
     */
    public function dropTable(string $tableName, bool $bothStructAndData = true)
    {
        /**
         * 在不存在的表上，执行删除操作，系统会报错。因此本处需要做出是否存在的判断。
         */
        $isExist = $this->isExistTable($tableName);
        if ($isExist) {
            $mate          = DatabaseClient::getMate($tableName);
            $realTableName = $mate->getTableRealName();

            if ($bothStructAndData) {
                $sql = "drop table `$realTableName`";
            } else {
                $sql = "truncate table `$realTableName`";
            }

            $mate->directlyExecute($sql);
        }
    }

    /**
     * 判断某个表是否存在
     * @param string $tableName
     * @return false
     */
    public function isExistTable(string $tableName): bool
    {
        try {
            $mate = DatabaseClient::getMate($tableName);
        } catch (Exception $ex) {
            return false;
        }

        $realTableName = $mate->getTableRealName();
        $sql           = "SHOW TABLES like '$realTableName';";
        $result        = $mate->directlyQuery($sql);

        if ($result && getLength($result) > 0) {
            return ArrayHelper::isContainsValue($result[0], $realTableName);
        } else {
            return false;
        }
    }

    /**
     * @param string $tableName 数据库表的名称,如果为None的话就直接从mate的构造函数中取数据库表名称
     * @return string
     */
    public function getTableDefinition(string $tableName): string
    {
        $mate          = DatabaseClient::getMate($tableName);
        $realTableName = $mate->getTableRealName();

        $sql    = "show create table `$realTableName`";
        $result = $mate->directlyQuery($sql);
        if ($result && getLength($result) > 0) {
            return $result[0]["Create Table"];
        } else {
            return "";
        }

        // result = DictHelper.get_value(result, "Create Table")
        // # TODO: 需要加入大小写字母判断
        // result = str.replace(result, "CREATE TABLE", "CREATE TABLE if not exists")
        // return result
    }

    /**
     * 获取数据表数据内容的插入sql语句
     * @param string $tableName
     * @param int    $row_count 复制数据的行数(-1表示所有的行数，0-n表示具体行数)
     * @return string
     */
    public function getContentSql(string $tableName, int $row_count = -1): string
    {
        $mate          = DatabaseClient::getMate($tableName);
        $realTableName = $mate->getTableRealName();

        if ($row_count < 0) {
            $select_sql = "SELECT * FROM `$realTableName`";

            # TODO:需要改成参数调用的方式
            # select_sql = "SELECT * FROM %s"
            # rows = mate.directly_query(select_sql, [real_table_name], FetchMode.MANY)
        } else {
            $select_sql = "SELECT * FROM `$realTableName` LIMIT $row_count";
        }

        $rows = $mate->directlyQuery($select_sql);

        $result = "";
        if ($rows) {
            $result = DatabaseHelper::buildInsertClause($realTableName, $rows);
        }

        return $result;
    }
}
