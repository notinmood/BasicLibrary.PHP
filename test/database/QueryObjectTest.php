<?php
/**
 * @file   : QueryObjectTest.php
 * @time   : 9:33
 * @date   : 2022/1/2
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\database;

use Hiland\Utils\Data\ReflectionHelper;
use Hiland\Utils\DataModel\DatabaseClient;
use Hiland\Utils\DataModel\ModelMate;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class QueryObjectTest extends TestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhere()
    {
        $mate = DatabaseClient::getMate("user");
        $queryObject = ReflectionHelper::getInstanceProperty(ModelMate::class, "queryObject", $mate);

        // dump($queryObject);
        // $mate->getCount();

        // $condition['class'] = "一";
        // $queryObject->where($condition);

        $condition['name'] = "lisi";
        $condition['class'] = "一";
        $queryObject->where($condition);
        $result = $queryObject->select();
        dump($result);
        // $queryObject = $queryObject;

        $actual = 1;
        $expected = 1;
        self::assertEquals($expected, $actual);

        // $mate->getCount();

    }
}