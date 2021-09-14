<?php
/**
 * @file   : CalculateMate.php
 * @time   : 15:51
 * @date   : 2021/9/14
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Utils\Data;

/**
 * 小数的方式精确计算
 * ════════════════════════
 * 调用方法
 * 保留两位  $accuracyCal = new AccuracyCal(0,2);
 * 以下代表表达式：0.1 + 2 - 1.1
 * 1、$result = $accuracyCal->number(0.1)->add(2)->sub(1.1)->getResult();
 * 2、$result = $accuracyCal(0.1)->add(2)->sub(1.1)->getResult();
 */
class CalculateMate
{
    //第一个要计算的数值
    public $number = 0;
    //精度计算时保留的小数点位数
    public $scale = 2;
    //计算结果
    public $result = 0;

    /**
     * AccuracyCal constructor.
     * @param int $scale 精确计算是保留的小数位数
     */
    public function __construct($number = 0, $scale = 2)
    {
        $this->number = $number;
        $this->scale = $scale;
    }

    /**
     * 第一个参与运算的数值，如果调用，需在最前面调用
     * @param $number 第一个要计算的数字
     */
    public function number($number)
    {
        $this->result = $this->number = $number;
        return $this;
    }

    /**
     * 精确计算：加
     * @param $number
     * @return $this
     */
    public function add($number)
    {
        $this->result = bcadd($this->result, $number, $this->scale);
        return $this;
    }

    /**
     * 精确计算：减
     * @param $number
     * @return $this
     */
    public function sub($number)
    {
        $this->result = bcsub($this->result, $number, $this->scale);
        return $this;
    }

    /**
     * 精确计算：乘
     * @param $number
     * @return $this
     */
    public function mul($number)
    {
        $this->result = bcmul($this->result, $number, $this->scale);
        return $this;
    }

    /**
     * 精确计算：除
     * @param $number
     * @return $this
     */
    public function div($number)
    {
        $this->result = bcdiv($this->result, $number, $this->scale);
        return $this;
    }

    /**
     * 获取计算结果
     * @return float
     */
    public function getResult()
    {
        return floatval($this->result);
    }
}