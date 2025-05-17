<?php

namespace Hiland\Biz\Stock\StockDataSource;

use Hiland\Biz\Stock\IStockDataSource;
use Hiland\Web\HttpClientHelper;
use Hiland\Web\RequestHelper;

/**
 *
 */
class StockDataSourceDemo implements IStockDataSource
{

    /**
     * @param mixed ...$stockCodes
     * @return string
     */
    function getRealTimeQuote(...$stockCodes): string
    {
        return "in demo";
    }
}
