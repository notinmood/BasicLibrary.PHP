<?php
/**
 * @file   : ModelDDL.php
 * @time   : 9:43
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\StringHelper;
use think\db\exception\PDOException;


/**
 * 内部方法之所以不设置静态，是为了扩展多种的数据库类型
 * (目前仅仅支持Mysql数据库类型)
 */
class ModelDDL
{
    /**
     * 复制数据表
     * @param string $originalTableName
     * @param string $newTableName
     * @param int    $includeDataRowCount 复制数据的行数(缺省0，表示不复制数据行;-1表示复制所有行)
     * @return void
     */
    public function duplicateTable($originalTableName, $newTableName = "", $includeDataRowCount = 0, $dropTableIfExist = True)
    {
        if (ObjectHelper::isEmpty($newTableName)) {
            $newTableName = $originalTableName . "__duplication__";
        }


        if ($dropTableIfExist) {
            $this->dropTable($newTableName, true);
        }


        $create_sql = $this->getTableDefinition($originalTableName);

        # TODO:需要使用正则表达式替换
        $create_sql = StringHelper::replace($create_sql, $originalTableName, $newTableName);

        $mate = DatabaseClient::getMate($originalTableName);
        $mate->directlyExecute($create_sql);

        $insert_sql = $this->getContentSql($originalTableName, $includeDataRowCount);
        $insert_sql = StringHelper::replace($insert_sql, $originalTableName, $newTableName);
        if ($insert_sql) {
            $mate . directly_exec(insert_sql);
        }

    }

    /**
     * 删除数据表
     * @param string $tableName         数据表名称
     * @param bool   $bothStructAndData 是否同时删除表结构和数据(true(缺省值)全部删除;false仅删除数据，保留表结构)
     * @return void
     */
    public function dropTable($tableName, $bothStructAndData = true)
    {
        /**
         * 在不存在的表上，执行删除操作，系统会报错。因此本处需要做出是否存在的判断。
         */
        $isExist = $this->isExistTable($tableName);
        if ($isExist) {
            $mate = DatabaseClient::getMate($tableName);
            $realTableName = $mate->getTableRealName();

            if ($bothStructAndData) {
                $sql = "drop table `{$realTableName}`";
            } else {
                $sql = "truncate table `{$realTableName}`";
            }

            $mate->directlyExecute($sql);
        }
    }

    //
    // def get_content_sql(self, table_name, row_count=-1):
    // """
    //         获取数据表数据内容的插入sql语句
    //         :param row_count:复制数据的行数(-1表示所有的行数，0-n表示具体行数)
    //         :param table_name:
    //         :return:
    //         """
    // pass

    /**
     * 判断某个表是否存在
     * @param $tableName
     * @return false
     */
    public function isExistTable($tableName)
    {
        try {
            $mate = DatabaseClient::getMate($tableName);
        } catch (PDOException $ex) {
            return false;
        }

        $realTableName = $mate->getTableRealName();
        $sql = "SHOW TABLES like '{$realTableName}';";
        $result = $mate->directlyQuery($sql);

        if ($result && getLength($result) > 0) {
            return ArrayHelper::isContainsValue($result[0], $realTableName);
        } else {
            return false;
        }
    }

    /**
     * @param string $tableName 数据库表的名称,如果为None的话就直接从mate的构造函数中取数据库表名称
     * @return void
     */
    public function getTableDefinition($tableName)
    {
        $mate = DatabaseClient::getMate($tableName);
        $realTableName = $mate->getTableRealName();

        $sql = "show create table `{$realTableName}`";
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
     * @param string $table_name
     * @param int    $row_count 复制数据的行数(-1表示所有的行数，0-n表示具体行数)
     * @return string
     */
    public function getContentSql($table_name, $row_count = -1)
    {
        $mate = DatabaseClient::getMate($table_name);
        $real_table_name = $mate->getTableRealName();

        if ($row_count < 0) {
            $select_sql = "SELECT * FROM `{$real_table_name}`";

            # TODO:需要改成参数调用的方式
            # select_sql = "SELECT * FROM %s"
            # rows = mate.directly_query(select_sql, [real_table_name], FetchMode.MANY)
        } else {
            $select_sql = "SELECT * FROM `{0}` LIMIT {1}" . format($real_table_name, $row_count);
        }

        $rows = $mate -> directlyQuery($select_sql);
        // dump($rows);

        $result = "";
        if ($rows) {
            $result = DatabaseHelper::buildInsertClause($real_table_name, $rows);
        }

        return $result;
    }
}