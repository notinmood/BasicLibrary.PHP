<?php

namespace Hiland\Biz\Stock;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class StockHelperTest extends TestCase
{

    public function testFormatStockCode()
    {
        $data = "600690";
        $expected = "sh600690";
        $actual = StockHelper::formatStockCode($data, "[sen]");
        assertEquals($expected, $actual);

        $data = "600690sh";
        $expected = "sh600690";
        $actual = StockHelper::formatStockCode($data, "[sen]");
        assertEquals($expected, $actual);

        $data = "600690ss";
        $expected = "sh.600690SH";
        $actual = StockHelper::formatStockCode($data, "[sen].", "[Sen]");
        assertEquals($expected, $actual);

        $data = "600690";
        $expected = "sh.600690";
        $actual = StockHelper::formatStockCode($data, "[sen].");
        assertEquals($expected, $actual);

        $data = "600690";
        $expected = "600690.SH";
        $actual = StockHelper::formatStockCode($data, "", ".[SEN]");
        assertEquals($expected, $actual);
    }

    public function testGetStockExchangeName()
    {
        $data = "600690";
        $expected = "SH";
        $actual = StockHelper::getStockExchangeName($data);
        self::assertEquals($expected, $actual);

        $data = "000917";
        $expected = "SZ";
        $actual = StockHelper::getStockExchangeName($data);
        self::assertEquals($expected, $actual);

        $data = "300001";
        $expected = "SZ";
        $actual = StockHelper::getStockExchangeName($data);
        self::assertEquals($expected, $actual);

        $data = "400001";
        $expected = "";
        $actual = StockHelper::getStockExchangeName($data);
        self::assertEquals($expected, $actual);

        $data = "3001";
        $expected = "";
        $actual = StockHelper::getStockExchangeName($data);
        self::assertEquals($expected, $actual);
    }

    public function testGetStandardStockCode()
    {
        $data = "000917";
        $expected = "000917";
        $actual = StockHelper::getStandardStockCode($data);
        self::assertEquals($expected, $actual);

        $data = "000.917";
        $expected = "";
        $actual = StockHelper::getStandardStockCode($data);
        self::assertEquals($expected, $actual);

        $data = "这是海尔的股票代码600690,2021年最高价格为38元。";
        $expected = "600690";
        $actual = StockHelper::getStandardStockCode($data);
        self::assertEquals($expected, $actual);

        $data = "这不是一个股票代码500690";
        $expected = "";
        $actual = StockHelper::getStandardStockCode($data);
        self::assertEquals($expected, $actual);
    }
}
