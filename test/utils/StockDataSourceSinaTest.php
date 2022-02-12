<?php

namespace Hiland\Test\utils;

use Hiland\Biz\Stock\StockDataSource\StockDataSourceSina;
use Hiland\Biz\Stock\StockRealTimeQuote;
use Hiland\Utils\Data\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use function PHPUnit\Framework\assertEquals;

class StockDataSourceSinaTest extends TestCase
{
    /**
     */
    public function testParseStockString()
    {
        $originalData = 'var hq_str_sh600690="海尔智家,26.670,26.720,27.200,27.330,26.120,27.200,27.210,45225256,1217253909.000,2800,27.200,22300,27.190,16300,27.180,35000,27.170,18800,27.160,20100,27.210,34200,27.220,19000,27.230,79800,27.240,43400,27.250,2021-10-29,15:00:02,00,"';
        $methodArgs   = [$originalData];
        $entity       = new StockDataSourceSina();

        $returnObject = ReflectionHelper::executeInstanceMethod(StockDataSourceSina::class, "parseStringToQuoteEntity", $entity, null, $methodArgs);

        if ($returnObject instanceof StockRealTimeQuote) {
            $actual = $returnObject["stockCode"];
            $expect = "600690";
            assertEquals($expect, $actual);

            $actual = $returnObject["stockName"];
            $expect = "海尔智家";
            assertEquals($expect, $actual);

            $actual = $returnObject["openPrice"];
            $expect = 26.670;
            assertEquals($expect, $actual);

            $actual = $returnObject["currentPrice"];
            $expect = 27.200;
            assertEquals($expect, $actual);

            $actual = $returnObject["buyPrice"];
            $expect = 27.200;
            assertEquals($expect, $actual);

            $actual = $returnObject["buy3Quantity"];
            $expect = 16300;
            assertEquals($expect, $actual);

            $actual = $returnObject["sale2Price"];
            $expect = 27.220;
            assertEquals($expect, $actual);

            $actual = $returnObject["exchangeTime"];
            $expect = "15:00:02";
            assertEquals($expect, $actual);
        } else {
            /**
             * 如果不是StockRealTimeQuote类型,用以下断言提示出错
             */
            self::assertInstanceOf(StockRealTimeQuote::class, $returnObject);
        }
    }
}
