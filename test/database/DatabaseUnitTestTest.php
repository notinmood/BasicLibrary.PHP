<?php
/**
 * @file   : DatabaseUnitTestTest.php
 * @time   : 17:44
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

use PHPUnit\Framework\TestCase;

class DatabaseUnitTestTest extends TestCase
{
    public function test_is_exist_table()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name, 2, false);

        $new_table_name = $biz->getNewTableName();
        $actual = $biz->getDDL()->isExistTable($new_table_name);
        $expected = true;
        self::assertEquals($expected, $actual);

        $biz->dispose();
        $actual = $biz->getDDL()->isExistTable($new_table_name);
        $expected = false;
        self::assertEquals($expected, $actual);
    }



    public function test_is_exist_table2()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name, 2);

        $new_table_name = $biz->getNewTableName();
        $actual = $biz->getDDL()->isExistTable($new_table_name);
        $expected = true;
        self::assertEquals($expected, $actual);

        return $new_table_name;
    }

    public function test_is_exist_table3(){
        /**
         * test_is_exist_table2创建完成biz的时候，数据库内有 DatabaseUnitTest 创建的表 ***_user_***;
         * 但，本地函数调用test_is_exist_table2使用完成后，数据库内的表 ***_user_*** 自动销毁。
         */
        $newTableName = $this->test_is_exist_table2();
        $actual =  DatabaseClient::getDDL()->isExistTable($newTableName);
        $expected = false;
        self::assertEquals($expected,$actual);
    }
}
