<?php
/**
 * @file   : ResultObjectTest.php
 * @time   : 11:00
 * @date   : 2021/10/13
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use Hiland\Utils\DataConstruct\ResultObject;
use PHPUnit\Framework\TestCase;

class ResultObjectTest extends TestCase
{
    public function testSetGetMiscItem()
    {
        $resultObject = $this->builderResultObject();

        $miscMyKey   = "myCity";
        $miscMyValue = "Qingdao青岛";
        $resultObject->setMiscItem($miscMyKey, $miscMyValue);
        $actual = $resultObject->getMiscItem($miscMyKey);
        self::assertEquals($miscMyValue, $actual);

        $miscMyKey = "mySchool";
        $actual    = $resultObject->getMiscItem($miscMyKey);
        self::assertNull($actual);

        $miscMyKey = "myJob";
        $expected  = "Engineer";
        $actual    = $resultObject->getMiscItem($miscMyKey, $expected);
        self::assertEquals($expected, $actual);

        /**
         * 以下几段代码，测试 ArrayAccess 接口的功能
         */
        $miscMyKey = "message";
        $expected  = "我是一个标题";
        $actual    = $resultObject[$miscMyKey];
        self::assertEquals($expected, $actual);

        $miscMyKey                = "myJob";
        $expected                 = "Engineer";
        $resultObject[$miscMyKey] = $expected;
        $actual                   = $resultObject[$miscMyKey];
        self::assertEquals($expected, $actual);

        $miscNoKey = "NoKey";
        $actual    = $resultObject[$miscNoKey];
        $expected  = null;
        self::assertEquals($expected, $actual);
    }

    public function testStringify()
    {
        $resultObject = $this->builderResultObject();
        $actual       = ResultObject::stringify($resultObject);
        $expected     = $this->jsonString;

        self::assertEquals($expected, $actual);
    }


    public function testParse()
    {
        $actual   = ResultObject::parse($this->jsonString);
        $expected = $this->builderResultObject();
        self::assertEquals($expected->data["a"], $actual->data->a);
        self::assertEquals($expected->misc->myNation, $actual->misc->myNation);
    }


    /**
     * @return ResultObject
     */
    private function builderResultObject(): ResultObject
    {
        $message      = "我是一个标题";
        $data         = ["a" => "AA", "b" => "BB"];
        $resultObject = new ResultObject(true, $message, $data);

        $miscMyKey   = "myNation";
        $miscMyValue = "China中国";
        $resultObject->setMiscItem($miscMyKey, $miscMyValue);

        return $resultObject;
    }

    private string $jsonString = '{"status":true,"message":"\u6211\u662f\u4e00\u4e2a\u6807\u9898","data":{"a":"AA","b":"BB"},"misc":{"myNation":"China\u4e2d\u56fd"}}';
}
