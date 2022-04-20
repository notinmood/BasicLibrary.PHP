<?php

namespace Hiland\Biz\Stock;

/**
 * 所有提供股票数据的数据源,都需要实现本接口
 */
interface IStockDataSource
{
    /**
     * 获取股票的实时报价
     * @param ...$stockCodes
     * @return mixed
     */
    function getRealTimeQuote(...$stockCodes);
}
