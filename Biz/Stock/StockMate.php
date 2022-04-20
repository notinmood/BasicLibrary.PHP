<?php

namespace Hiland\Biz\Stock;

use Hiland\Biz\Stock\StockDataSource\StockDataSourceSina;
use Hiland\Utils\IO\PathHelper;

/**
 *
 */
class StockMate
{
    private $stockDataSource = null;

    public function __construct($dataSourceName = "Sina")
    {
        $this->stockDataSource = self::getDataSource($dataSourceName);
    }

    private static function getDataSource($sourceName)
    {
        /** @noinspection all */
        $targetParserType = "StockDataSource{$sourceName}";
        $targetParserClass = "Hiland\\Biz\\Stock\\StockDataSource\\$targetParserType";
        $targetFileBaseName = "$targetParserType.php";
        $targetFileFullName = PathHelper::combine(__DIR__, "StockDataSource", $targetFileBaseName);
        if (file_exists($targetFileFullName)) {
            return new $targetParserClass();
        } else {
            /**
             * 默认使用新浪的数据源
             */
            return new StockDataSourceSina();
        }
    }

    /**
     * @param ...$stockCode
     * @return array 数组的元素是StockRealTimeQuote类型的对象
     */
    public function getRealTimeQuote(...$stockCode)
    {
        return $this->stockDataSource->getRealTimeQuote(...$stockCode);
    }
}
