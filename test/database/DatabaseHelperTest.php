<?php
/**
 * @file   : DatabaseHelperTest.php
 * @time   : 14:35
 * @date   : 2021/12/31
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{
    public function testWrapData()
    {
        $actual   = DatabaseHelper::wrapSqlValue("qingdao");
        $expected = "'qingdao'";
        self::assertEquals($expected, $actual);

        $actual   = DatabaseHelper::wrapSqlValue(true);
        $expected = true;
        self::assertEquals($expected, $actual);
    }

    public function testBuildInsertClause()
    {
        $entity1["id"]   = 1;
        $entity1["name"] = "zhangsan";
        $entity1["age"]  = 20;

        $actual   = DatabaseHelper::buildInsertClause("student", $entity1);
        $expected = "INSERT INTO `student` (`id`,`name`,`age`) VALUES (1,'zhangsan',20);";
        self::assertEquals($expected, $actual);

        $entity2["id"]   = 2;
        $entity2["name"] = "lisi";
        $entity2["age"]  = 21;
        $list[]= $entity1;
        $list[]= $entity2;
        $actual   = DatabaseHelper::buildInsertClause("student", $list);
        $expected = "INSERT INTO `student` (`id`,`name`,`age`) VALUES (1,'zhangsan',20), (2,'lisi',21);";

        self::assertEquals($expected, $actual);
    }
}
