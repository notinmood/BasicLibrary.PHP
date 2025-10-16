<?php

namespace Hiland\Test\data;

use Hiland\Biz\Stock\StockMate;
use Hiland\Data\ObjectHelper;
use PHPUnit\Framework\TestCase;

class StockMateTest extends TestCase
{
    public function testGetRealTimeQuote(): void
    {
        $mate     = new StockMate("Demo");
        $expected = "in demo";
        $actual   = $mate->getRealTimeQuote("000917");
        self::assertEquals($expected, $actual);

        $mate = new StockMate();

        $returnObject = $mate->getRealTimeQuote("000917", "600690");

        $actual   = ObjectHelper::getLength($returnObject);
        $expected = 2;
        self::assertEquals($expected, $actual);

        $actual   = $returnObject[0]["stockName"];
        $expected = "电广传媒";
        self::assertEquals($expected, $actual);

        $actual   = $returnObject[1]["stockCode"];
        $expected = "600690";
        self::assertEquals($expected, $actual);


        $returnObject = $mate->getRealTimeQuote("", "600690");

        $actual   = ObjectHelper::getLength($returnObject);
        $expected = 1;
        self::assertEquals($expected, $actual);
    }
}
