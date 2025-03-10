<?php
/**
 * @file   : RollingWindow.php
 * @time   : 16:49
 * @date   : 2025/3/10
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\DataConstruct;

use Hiland\data\NumberHelper;

class RollingWindow
{
    private array $data;

    public function __construct(array $arrData)
    {
        $this->data = $arrData;
    }

    /**
     * @desc: 向原始数组追加数据
     * @param array ...$nums
     */
    public function appendData(array ...$nums): void
    {
        $this->data = array_merge($this->data, ...$nums);
    }

    /**
     * @desc: 计算移动平均值
     * @param int $windowSize 窗口大小
     * @return array 移动平均值数组
     */
    public function getMovingAverage(int $windowSize): array
    {
        return NumberHelper::getMovingAverage($this->data, $windowSize);
    }
}
