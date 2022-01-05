<?php
/**
 * @file   : ModelDDLTest.php
 * @time   : 10:21
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

use Hiland\Utils\Data\StringHelper;
use PHPUnit\Framework\TestCase;

class ModelDDLTest extends TestCase
{
    public function testGetTableDefinition()
    {
        $ddl = DatabaseClient::getDDL();
        $tableName = "user";
        $result = $ddl->getTableDefinition($tableName);
        $actual = StringHelper::isStartWith($result, "CREATE TABLE");
        $expected = true;
        self::assertEquals($expected, $actual);
    }

    public function testIsExistTable()
    {
        $ddl = DatabaseClient::getDDL();
        $tableName = "user";
        $actual = $ddl->isExistTable($tableName);
        $expected = true;
        self::assertEquals($expected, $actual);

        $ddl = DatabaseClient::getDDL();
        $tableName = "our";
        $actual = $ddl->isExistTable($tableName);
        $expected = false;
        self::assertEquals($expected, $actual);
    }

    public function testGetContentSql()
    {
        $ddl = DatabaseClient::getDDL();
        $tableName = "user";
        $actual = $ddl->getContentSql($tableName);
        $expected = "INSERT INTO `tmp_user` (`id`,`name`,`birthday`,`email`,`class`,`score`) VALUES (1,'zhangsan','2021-12-24 09:07:05','266000@sina.com','一',66), (2,'lisi','2021-12-15 09:07:26','277521@qq.com','三',93), (3,'zhangsan','2021-12-24 09:07:05','aa@qq.com','二',88), (4,'hah','2021-12-22 10:07:47','wps@foxmail.com','一',97);";
        self::assertEquals($expected, $actual);

        $actual = $ddl->getContentSql($tableName, 1);
        $expected = "INSERT INTO `tmp_user` (`id`,`name`,`birthday`,`email`,`class`,`score`) VALUES (1,'zhangsan','2021-12-24 09:07:05','266000@sina.com','一',66);";
        self::assertEquals($expected, $actual);

        $actual = $ddl->getContentSql($tableName, 0);
        $expected = "";
        self::assertEquals($expected, $actual);
    }

    public function testDuplicateTableAndDropTable()
    {
        $ddl = DatabaseClient::getDDL();
        $tableName = "user";
        $newTableName="user_aa__";
        $ddl->duplicateTable($tableName,$newTableName);

        $actual = $ddl->isExistTable($newTableName);
        $expected = true;
        self::assertEquals($expected, $actual);

        $ddl->dropTable($newTableName);
        $actual = $ddl->isExistTable($newTableName);
        $expected = false;
        self::assertEquals($expected, $actual);
    }
}
