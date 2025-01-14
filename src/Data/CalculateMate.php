<?php
/**
 * @file   : CalculateMate.php
 * @time   : 15:51
 * @date   : 2021/9/14
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Data;

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
    public int $precision = 2;
    //计算结果
    public int $result = 0;

    /**
     * 精确计算器的构造方法
     * @param int $precision 精确计算是保留的小数位数,缺省为 2位小数的精度
     */
    public function __construct($number = 0, int $precision = 2)
    {
        $this->number    = $number;
        $this->precision = $precision;
    }

    /**
     * 第一个参与运算的数值，如果调用，需在最前面调用
     * @param mixed $number 第一个要计算的数字
     */
    public function number($number): CalculateMate
    {
        $this->result = $this->number = $number;
        return $this;
    }

    /**
     * 精确计算：加
     * @param $number
     * @return $this
     */
    public function add($number): CalculateMate
    {
        $this->result = bcadd($this->result, $number, $this->precision);
        return $this;
    }

    /**
     * 精确计算：减
     * @param $number
     * @return $this
     */
    public function subtract($number): CalculateMate
    {
        $this->result = bcsub($this->result, $number, $this->precision);
        return $this;
    }

    /**
     * 精确计算：乘
     * @param $number
     * @return $this
     */
    public function multiply($number): CalculateMate
    {
        $this->result = bcmul($this->result, $number, $this->precision);
        return $this;
    }

    /**
     * 精确计算：除
     * @param $number
     * @return $this
     */
    public function divide($number): CalculateMate
    {
        $this->result = bcdiv($this->result, $number, $this->precision);
        return $this;
    }

    /**
     * 获取计算结果
     */
    public function getResult(): float
    {
        return floatval($this->result);
    }
}
