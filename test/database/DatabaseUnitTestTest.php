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

use Hiland\Test\database\_res\UserMocker;
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

    public function test_is_exist_table3()
    {
        /**
         * test_is_exist_table2创建完成biz的时候，数据库内有 DatabaseUnitTest 创建的表 ***_user_***;
         * 但，本地函数调用test_is_exist_table2使用完成后，数据库内的表 ***_user_*** 自动销毁。
         */
        $newTableName = $this->test_is_exist_table2();
        $actual = DatabaseClient::getDDL()->isExistTable($newTableName);
        $expected = false;
        self::assertEquals($expected, $actual);
    }

    public function testGet1()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);

        $mate = $biz->getMate();
        $entity = $mate->get(3);
        $actual = $entity["name"];
        $expected = "zhangsan";
        self::assertEquals($expected, $actual);
    }

    public function testGet2()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);

        $mate = $biz->getMate();
        $entity = $mate->get(3, "id");
        $actual = $entity["name"];
        $expected = "zhangsan";
        self::assertEquals($expected, $actual);

        $actual = $entity;
        $expected = (new UserMocker())->getMocker();
        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testWhere1()
    {
        $table_name = "student";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $condition[DatabaseEnum::WHEREOR] = ["sid" => 2, "score" => 87];
        $result = $mate->select($condition, "sid asc");

        $actual = $result->count();
        $expected = 2;
        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testWhere2()
    {
        $table_name = "student";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $condition[DatabaseEnum::WHEREOR] = ["sid" => 2, "class" => "三"];
        $result = $mate->select($condition, "sid asc");
        $actual = $result->count();
        $expected = 4;
        self::assertEquals($expected, $actual);
    }

    public function testWhere3()
    {
        $table_name = "student";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $condition[DatabaseEnum::WHEREAND] = ["score" => 100, "class" => "三"];
        $result = $mate->select($condition, "sid asc");
        $actual = $result->count();
        $expected = 1;
        self::assertEquals($expected, $actual);
    }

    public function testWhere4()
    {
        $table_name = "student";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $condition = ["score" => 100, "class" => "三"];
        $result = $mate->select($condition, "sid asc");
        $actual = $result->count();
        $expected = 1;
        self::assertEquals($expected, $actual);
    }

    public function testWhere5()
    {
        $table_name = "student";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $condition = ["score" => [">=" => 80]];
        $result = $mate->select($condition, "sid asc");
        $actual = $result->count();
        $expected = 5;
        self::assertEquals($expected, $actual);
    }

    public function testWhere6()
    {
        $table_name = "student";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        /**
         * 条件设置方法1
         */
        // $condition = ["score" =>[["<" => 90] ,[">=" => 80]]];

        /**
         * 条件设置方法2
         */
        $condition["score"] = [["<" => 90], [">=" => 80]];
        $condition["class"] = "三";
        $result = $mate->select($condition, "sid asc");
        $actual = $result->count();
        $expected = 1;
        self::assertEquals($expected, $actual);
    }

    public function testMock()
    {
        $mock = new UserMocker();
        $result1 = $mock->getMocker();
        $result1["email"] = "xxx@bb.com";

        $mock = new UserMocker(["email" => "xxx@bb.com"]);
        $result2 = $mock->getMocker();
        self::assertEquals($result1, $result2);
    }

    public function testInteract1()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);

        $mate = $biz->getMate();

        $data["id"] = 3;
        $data["email"] = "bb@qq.com";
        $data["class"] = "四";
        $data["birthday"] = "2021-12-31 00:13:25";
        $data["score"] = 88;

        $recordID = $mate->interact($data);
        $actual = 3;
        $expected = $recordID;
        self::assertEquals($expected, $actual);

        $entity = $mate->get(3);
        $expected = "zhangsan";
        $actual = $entity["name"];
        self::assertEquals($expected, $actual);

        $mock = new UserMocker($data);
        $expected = $mock->getMocker();
        self::assertEquals($expected, $entity);
    }

    public function testInteract2()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $data["name"] = "zhao";
        $data["email"] = "mpp@qq.com";
        $data["class"] = "四";
        $data["birthday"] = "2021-12-31 01:13:25";
        $data["score"] = 88;

        $recordID = $mate->interact($data);

        $entity = $mate->find(["email" => "mpp@qq.com"], ["score" => 88]);
        $expected = "zhao";
        $actual = $entity["name"];
        self::assertEquals($expected, $actual);
    }


    public function testDelete()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $mate->delete(["id" => 2]);
        $actual = $mate->get(2);
        $expected = null;
        self::assertEquals($expected, $actual);
    }

    public function testGetCount()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $map["score"] = [[">" => 80], ["<" => 90]];
        $actual = $mate->getCount($map);

        $expected = 1;
        self::assertEquals($expected, $actual);
    }

    public function testGetValue()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $actual = $mate->getValue(2, "email");
        $expected = "277521@qq.com";
        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testSetValue()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $actual = $mate->setValue(2, "email", "33@ww.com");
        $expected = 1;
        self::assertEquals($expected, $actual);

        $result = $mate->get(2);
        $actual = $result["name"];
        $expected = "lisi";
        self::assertEquals($expected, $actual);

        $actual = $result["email"];
        $expected = "33@ww.com";
        self::assertEquals($expected, $actual);
    }

    /**
     * 测试WHERE条件设置的幂等性(即，多次调用WHERE条件的效果不会累计)
     * @return void
     */
    public function testWhereIdempotence()
    {
        $table_name = "user";
        $biz = new DatabaseUnitTest($table_name);
        $mate = $biz->getMate();

        $result = $mate->get(1);
        $actual = $result["class"];
        $expected = "一";
        self::assertEquals($expected, $actual);

        $result = $mate->get(2);
        $actual = $result["class"];
        $expected = "三";
        self::assertEquals($expected, $actual);

        $result = $mate->select(["score" => [">=" => 90]]);
        $actual = $result->count();
        $expected = 2;
        self::assertEquals($expected, $actual);
    }
}
