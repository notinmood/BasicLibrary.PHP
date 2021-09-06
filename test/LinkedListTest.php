<?php
/**
 * @file   : LinkedListTest.php
 * @time   : 15:38
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataConstruct;

use PHPUnit\Framework\TestCase;

class LinkedListTest extends TestCase
{
    private function prepareData()
    {
        $linkedList = new LinkedList();

        $value = "beijing";
        $linkedList->addTail($value);

        $value = "shanghai";
        $linkedList->addTail($value);

        $value = "guangzhou";
        $linkedList->addTail($value);
        return $linkedList;
    }

    public function testGet()
    {
        $linkedList = $this->prepareData();

        $actual = $linkedList->get(0);
        $expect = "beijing";
        self::assertEquals($expect, $actual);
    }

    public function testGetNode()
    {
        $linkedList = $this->prepareData();

        $actual = $linkedList->getNode(0);
        $expect = "beijing";
        self::assertEquals($expect, $actual->value);
    }

    public function testIsContains()
    {
        $linkedList = $this->prepareData();

        $value = "beijing";
        $actual = $linkedList->isContains($value);
        $this->assertTrue($actual);

        $value = "guangzhou";
        $actual = $linkedList->isContains($value);
        $this->assertTrue($actual);

        $value = "shenzhen";
        $actual = $linkedList->isContains($value);
        $this->assertFalse($actual);
    }


    public function testAddHead()
    {
        // $linkedList = $this-> prepareData();
        $linkedList = new LinkedList();


        $value1 = "beijing";
        $newNode = $linkedList->addHead($value1);

        $actual = $linkedList->get(0);
        self::assertEquals($value1, $actual);
        self::assertEquals($value1, $newNode->value);


        $value2 = "shanghai";
        $linkedList->addHead($value2);

        $actual = $linkedList->get(0);
        self::assertEquals($value2, $actual);
        $actual = $linkedList->get(1);
        self::assertEquals($value1, $actual);
    }


    public function testAddTail()
    {
        $linkedList = new LinkedList();


        $value = "beijing";
        $linkedList->addTail($value);

        $actual = $linkedList->get(0);
        self::assertEquals($value, $actual);
        $linkedList = new LinkedList();

        $value = "shanghai";
        $linkedList->addTail($value);
        $lastIndex = ($linkedList->size) - 1;
        $actual = $linkedList->get($lastIndex);
        self::assertEquals($value, $actual);
    }

    public function testInsert()
    {
        $linkedList = new LinkedList();

        $value = "beijing";
        $linkedList->addTail($value);

        $value = "shanghai";
        $linkedList->addTail($value);

        $value = "guangzhou";
        $index = 1;
        $linkedList->insert($index, $value);

        $actual = $linkedList->get($index);
        self::assertEquals($value, $actual);
    }

    public function testUpdate()
    {
        $linkedList = $this->prepareData();
        $index = 1;
        $value = "qingdao";
        $linkedList->update($index, $value);
        $actual = $linkedList->get($index);
        self::assertEquals($value, $actual);
    }

    public function testRemove()
    {
        $linkedList = $this->prepareData();

        self::assertEquals(3, $linkedList->size);

        $index = 1;
        $linkedList->remove($index);

        self::assertEquals(2, $linkedList->size);
        $actual = $linkedList->get($index);
        // dump($actual);
        self::assertNotEquals("shanghai", $actual);

        $index = 0;
        $linkedList->remove($index);
        $actual = $linkedList->get($index);
        self::assertNotEquals("beijing", $actual);
    }
}
