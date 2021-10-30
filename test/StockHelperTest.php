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
    }
}
