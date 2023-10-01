<?php

namespace Hiland\Biz\Stock;

use ArrayAccess;
use Hiland\Utils\Data\ObjectHelper;

/**
 * 股票实时报价
 */
class StockRealTimeQuote implements ArrayAccess
{
    public string $stockCode = "";
    public string $stockName = "";
    public float $openPrice = 0.0;
    public float $preClosePrice = 0.0;
    public float $currentPrice = 0.0;
    public float $highestPrice = 0.0;
    public float $lowestPrice = 0.0;
    public float $buyPrice = 0.0; //竞买价，即“买一”报价；
    public float $salePrice = 0.0; //竞卖价，即“卖一”报价；

    /**
     * 成交的股票数，由于股票交易以一百股为基本单位，所以在使用时，通常把该值除以一百；
     * @var int
     */
    public int $exchangedQuantity = 0;

    /**
     * 成交金额，单位为“元”，为了一目了然，通常以“万元”为成交金额的单位，所以通常把该值除以一万；
     * @var float
     */
    public float $exchangedAmount = 0.0;

    public int $buy1Quantity = 0;
    public float $buy1Price = 0.0;

    public int $buy2Quantity = 0;
    public float $buy2Price = 0.0;

    public int $buy3Quantity = 0;
    public float $buy3Price = 0.0;

    public int $buy4Quantity = 0;
    public float $buy4Price = 0.0;

    public int $buy5Quantity = 0;
    public float $buy5Price = 0.0;

    public int $sale1Quantity = 0;
    public float $sale1Price = 0.0;

    public int $sale2Quantity = 0;
    public float $sale2Price = 0.0;

    public int $sale3Quantity = 0;
    public float $sale3Price = 0.0;

    public int $sale4Quantity = 0;
    public float $sale4Price = 0.0;

    public int $sale5Quantity = 0;
    public float $sale5Price = 0.0;

    /**
     * 日期
     * @var null
     */
    public $exchangeDate = null;
    /**
     * 时间
     * @var null
     */
    public $exchangeTime = null;

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return ObjectHelper::isExist($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}
