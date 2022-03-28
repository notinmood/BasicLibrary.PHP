<?php
/**
 * @file   : ModelMateTest.php
 * @time   : 10:09
 * @date   : 2021/12/31
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\database;

use Hiland\Utils\Data\ReflectionHelper;
use Hiland\Utils\DataModel\DatabaseClient;
use Hiland\Utils\DataModel\ModelMate;
use PHPUnit\Framework\TestCase;

/**
 * 对Mate的测试，大部分都统一在 DatabaseUnitTestTest.php 内进行。
 */
class ModelMateTest extends TestCase
{
    public function testIndex()
    {
        $actual = 1;
        $expected = 1;
        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testWhere()
    {
        $mate = DatabaseClient::getMate("user");
        $queryObject = ReflectionHelper::getInstanceProperty(ModelMate::class, "queryObject", $mate);

        /**
         * 1. $queryObject->where() 的原生写法
         */
        $queryObject->where("id", "<", 3);
        $queryObject->where("class", "一");
        $result1 = $queryObject->select();

        /**
         * 2 采用 mate的写法(mate内部对 传递给$queryObject的where条件做了解析处理)
         */
        $condition['id'] = ["<" => 3];
        $condition['class'] = "一";
        $result2 = $mate->select($condition);

        $actual = $result1;
        $expected = $result2;
        self::assertEquals($expected, $actual);
    }
}