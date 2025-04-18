<?php

namespace Hiland\Data;

use ReflectionException;

/**
 * 数据集辅助类
 * @author devel
 */
class DBSetHelper
{
    /**
     * 获取数据集中第 0行中的某个字段值
     * @param array  $dbSet
     * @param string $fieldName
     * @param int    $rowIndex
     * @return mixed
     */
    public static function getSingleValue(array $dbSet, string $fieldName, int $rowIndex = 0)
    {
        $fieldValue = null;
        $rowData    = self::getRow($dbSet, $rowIndex);
        if ($rowData != null) {
            $fieldValue = $rowData[$fieldName];
        }

        return $fieldValue;
    }

    /**
     * 获取数据集中第某行的信息
     * @param array $dbSet    数据集（二维数组）
     * @param int   $rowIndex 行索引值
     * @return array 数据集中的某行（一维数组）
     */
    public static function getRow(array $dbSet, int $rowIndex = 0): ?array
    {
        $rowData = null;
        if ($dbSet != null && count($dbSet) > $rowIndex) {
            $rowData = $dbSet[$rowIndex];
        }

        return $rowData;
    }

    /**
     * 友好地显示数据集信息
     * @param array      $dbSet
     *            数据集
     * @param array|null $mapArray
     *            转换数组（多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的枚举）,例如
     *            array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))
     * @param array|null $funcArray
     *            函数数组，多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的函数，
     *            此函数仅支持一个参数，即将数据库内的值作为本函数的参数
     *            1、如果是全局函数可以直接写函数的名称，
     *            2、如果是类的方法，请使用如下格式进行书写 nameSpace/className|methodName，
     *            其中如果是直接使用调用方类内的其他某个方法，nameSpace/className可以直接用__CLASS__表示
     * @return array 友好显示的数据集信息
     * @throws ReflectionException
     */
    public static function displayFriendly(array &$dbSet, array $mapArray = null, array $funcArray = null): array
    {
        return ArrayHelper::displayDbSetFriendly($dbSet, $mapArray, $funcArray);
    }
}
