<?php

namespace Hiland\Biz\Stock\StockDataSource;

use Hiland\Biz\Stock\IStockDataSource;
use Hiland\Biz\Stock\StockHelper;
use Hiland\Biz\Stock\StockRealTimeQuote;
use Hiland\Data\ObjectHelper;
use Hiland\Data\StringHelper;
use Hiland\Web\HttpClientHelper;

/**
 * 新浪的证券数据源
 */
class StockDataSourceSina implements IStockDataSource
{

    /**
     * @param mixed ...$stockCodes
     * @return array 数组的元素是StockRealTimeQuote类型的对象
     */
    public function getRealTimeQuote(...$stockCodes): array
    {
        $stockCodeString = "";
        foreach ($stockCodes as $item) {
            $stockCodeString .= StockHelper::formatStockCode($item, "[sen]") . ",";
        }

        $baseUrl = "http://hq.sinajs.cn/list={$stockCodeString}";
        $content = HttpClientHelper::get($baseUrl);

        /**
         * 新浪传递过来的编码是GB18030,通过类似PostManAPI这样的工具可以查看到新浪Response的编码格式
         */
        $contentString = mb_convert_encoding($content, "utf-8", 'GB18030');
        $contentArray = StringHelper::explode($contentString, ";\n");

        $resultArray = [];
        foreach ($contentArray as $contentItem) {
            if ($contentItem) {
                $resultArray[] = $this->parseStringToQuoteEntity($contentItem);
            }
        }

        return $resultArray;
    }

    private function parseStringToQuoteEntity($stockString): StockRealTimeQuote
    {
        $parts = StringHelper::explode($stockString, "=");
        $firstPart = $parts[0];
        $secondPart = $parts[1];

        $result = "";
        $quote = new StockRealTimeQuote();

        $stockCode = StockHelper::formatStockCode($firstPart, "", "");
        $quote->stockCode = $stockCode;

        $secondPart = StringHelper::replace($secondPart, "\"", "");
        $secondArray = StringHelper::explode($secondPart, ",");

        $stockName = ObjectHelper::getMember($secondArray, 0, "");
        $quote->stockName = $stockName;

        $openPrice = ObjectHelper::getMember($secondArray, 1, 0);
        $quote->openPrice = $openPrice;

        $preClosePrice = ObjectHelper::getMember($secondArray, 2, 0);
        $quote->preClosePrice = $preClosePrice;

        $currentPrice = ObjectHelper::getMember($secondArray, 3, 0);
        $quote->currentPrice = $currentPrice;

        $highestPrice = ObjectHelper::getMember($secondArray, 4, 0);
        $quote->highestPrice = $highestPrice;

        $lowestPrice = ObjectHelper::getMember($secondArray, 5, 0);
        $quote->lowestPrice = $lowestPrice;

        $buyPrice = ObjectHelper::getMember($secondArray, 6, 0);
        $quote->buyPrice = $buyPrice;

        $salePrice = ObjectHelper::getMember($secondArray, 7, 0);
        $quote->salePrice = $salePrice;

        $exchangedQuantity = ObjectHelper::getMember($secondArray, 8, 0);
        $quote->exchangedQuantity = $exchangedQuantity;

        $exchangedAmount = ObjectHelper::getMember($secondArray, 9, 0);
        $quote->exchangedAmount = $exchangedAmount;

        $buy1Quantity = ObjectHelper::getMember($secondArray, 10, 0);
        $quote->buy1Quantity = $buy1Quantity;

        $buy1Price = ObjectHelper::getMember($secondArray, 11, 0);
        $quote->buy1Price = $buy1Price;

        $buy2Quantity = ObjectHelper::getMember($secondArray, 12, 0);
        $quote->buy2Quantity = $buy2Quantity;

        $buy2Price = ObjectHelper::getMember($secondArray, 13, 0);
        $quote->buy2Price = $buy2Price;

        $buy3Quantity = ObjectHelper::getMember($secondArray, 14, 0);
        $quote->buy3Quantity = $buy3Quantity;

        $buy3Price = ObjectHelper::getMember($secondArray, 15, 0);
        $quote->buy3Price = $buy3Price;

        $buy4Quantity = ObjectHelper::getMember($secondArray, 16, 0);
        $quote->buy4Quantity = $buy4Quantity;

        $buy4Price = ObjectHelper::getMember($secondArray, 17, 0);
        $quote->buy4Price = $buy4Price;

        $buy5Quantity = ObjectHelper::getMember($secondArray, 18, 0);
        $quote->buy5Quantity = $buy5Quantity;

        $buy5Price = ObjectHelper::getMember($secondArray, 19, 0);
        $quote->buy5Price = $buy5Price;

        $sale1Quantity = ObjectHelper::getMember($secondArray, 20, 0);
        $quote->sale1Quantity = $sale1Quantity;

        $sale1Price = ObjectHelper::getMember($secondArray, 21, 0);
        $quote->sale1Price = $sale1Price;

        $sale2Quantity = ObjectHelper::getMember($secondArray, 21, 0);
        $quote->sale2Quantity = $sale2Quantity;

        $sale2Price = ObjectHelper::getMember($secondArray, 23, 0);
        $quote->sale2Price = $sale2Price;

        $sale3Quantity = ObjectHelper::getMember($secondArray, 24, 0);
        $quote->sale3Quantity = $sale3Quantity;

        $sale3Price = ObjectHelper::getMember($secondArray, 25, 0);
        $quote->sale3Price = $sale3Price;

        $sale4Quantity = ObjectHelper::getMember($secondArray, 26, 0);
        $quote->sale4Quantity = $sale4Quantity;

        $sale4Price = ObjectHelper::getMember($secondArray, 27, 0);
        $quote->sale4Price = $sale4Price;

        $sale5Quantity = ObjectHelper::getMember($secondArray, 28, 0);
        $quote->sale5Quantity = $sale5Quantity;

        $sale5Price = ObjectHelper::getMember($secondArray, 29, 0);
        $quote->sale5Price = $sale5Price;

        $exchangeDate = ObjectHelper::getMember($secondArray, 30, "");
        $quote->exchangeDate = $exchangeDate;

        $exchangeTime = ObjectHelper::getMember($secondArray, 31, "");
        $quote->exchangeTime = $exchangeTime;

        return $quote;
    }
}
