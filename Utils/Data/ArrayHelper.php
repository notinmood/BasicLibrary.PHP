<?php

namespace Hiland\Utils\Data;

class ArrayHelper
{
    /**
     * 数组转简单对象
     *
     * @param array $array 名值对类型的一维或者多维数组
     * @return object
     */
    public static function arrayToObject($array)
    {
        return ObjectHelper::arrayToObject($array);
    }

    /**
     * 将数组转变为xml数据
     *
     * @param array $array
     *            有名值对类型的数组
     * @param string $charset
     *            xml数据的编码（缺省情况下为utf-8）
     * @param string $rootname
     *            xml数据的跟节点名称
     * @param bool $includeHeader 是否在生成的xml文档中包含xml头部声明
     * @return string
     */
    public static function Toxml($array, $rootname = 'myxml', $includeHeader = true, $charset = 'utf8')
    {
        $xml = '';
        if ($includeHeader) {
            $xml .= '<?xml version="1.0" encoding="' . $charset . '" ?>';
        }
        $xml .= "<$rootname>";
        $xml .= self::convert2xml($array);
        $xml .= "</$rootname>";
        return $xml;
    }

    /**
     * @param $value
     * @return string
     */
    private static function convert2xml($value)
    {
        $xml = '';
        if ((!is_array($value) && !is_object($value)) || count($value) <= 0) {
            // do nothing;
        } else {
            foreach ($value as $key => $val) {
                if (is_array($val) || is_object($val)) { // 判断是否是数组，或者，对像
                    $xml .= "<" . $key . ">";
                    $xml .= self::convert2xml($val); // 是数组或者对像就的递归调用
                    $xml .= "</" . $key . ">";
                } else {
                    if (is_numeric($val)) {
                        $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
                    } else {
                        $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
                    }
                }
            }
        }

        return $xml;
    }

    /**
     * 对数组的key和value进行翻转
     *
     * @param array $originalArray
     *            有简单值构成key和value的名值对一维数组
     * @return array
     */
    public static function exchangeKeyValue($originalArray)
    {
        $newarray = array();
        foreach ($originalArray as $key => $value) {
            $newarray[$value] = $key;
        }

        return $newarray;
    }

    /**
     * 将有别名标示的二维数组转换为一维数组
     *
     * @param array $originalArray
     *            原始的二维数组
     * @param string $newKeyName
     *            二维数组中的某个元素的名称，其对应的值将作为一维数组的key
     *            如果这个值为空，那么将会把一维数组的key作为此值
     * @param string $newValueName
     *            二维数组中的某个元素的名称，其对应的值将作为一维数组的value
     *            如果这个值为空，那么将会把一维数组的key作为此值
     * @return array 转换后的一维数组
     * @example 待转换的有别名的二维数组类似如下：
     *          $originalarray => array(
     *          'UNOUT' => array(
     *          'value' => 0,
     *          'display' => '未出局'
     *          ),
     *          'PARTIALOUTASLOCK' => array(
     *          'value' => 1,
     *          'display' => '锁定未出局'
     *          ),
     *          'OUT' => array(
     *          'value' => 10,
     *          'display' => '出局'
     *          )
     *          )
     *
     *
     *          1、extract2DTo1D($originalarray, 'value', 'display')的结果为
     *          array(3) {
     *          [0] => string(9) "未出局"
     *          [1] => string(15) "锁定未出局"
     *          [10] => string(6) "出局"
     *          }
     *
     *          2、extract2DTo1D($originalarray, 'value')的结果为
     *          array(3) {
     *          [0] => string(5) "UNOUT"
     *          [1] => string(16) "PARTIALOUTASLOCK"
     *          [10] => string(3) "OUT"
     *          }
     *
     *          3、extract2DTo1D($originalarray,'','display')的结果为
     *          array(3) {
     *          ["UNOUT"] => string(9) "未出局"
     *          ["PARTIALOUTASLOCK"] => string(15) "锁定未出局"
     *          ["OUT"] => string(6) "出局"
     *          }
     *
     *          4、extract2DTo1D($originalarray)的结果为
     *          array(3) {
     *          ["UNOUT"] => string(5) "UNOUT"
     *          ["PARTIALOUTASLOCK"] => string(16) "PARTIALOUTASLOCK"
     *          ["OUT"] => string(3) "OUT"
     *          }
     *
     */
    public static function extract2DTo1D($originalArray, $newKeyName = '', $newValueName = '')
    {
        $newArray = array();
        foreach ($originalArray as $k => $v) {
            if (empty($newKeyName)) {
                if (empty($newValueName)) {
                    $newArray[$k] = $k;
                } else {
                    $newArray[$k] = $v[$newValueName];
                }
            } else {
                if (empty($newValueName)) {
                    $newArray[$v[$newKeyName]] = $k;
                } else {
                    $newArray[$v[$newKeyName]] = $v[$newValueName];
                }
            }
        }

        return $newArray;
    }

    /**
     * 把第二维度中的value为名值对的 二维数组，转换为一维数组
     * @param $originalArray
     * @param string $convertNodeName
     * @return mixed
     * @example
     * 原二维数组为
     * {
     * ["id"] => "82"
     * ["remark"] => 'hello',
     * ["time"] => "2016-06-15 15:23:21",
     * ["contact"] =>
     * {
     * ["id"] => "182",
     * ["name"] => "解然",
     * ["phone"] => "18888888888",
     * }
     * }
     * 经过转换后为
     *
     * {
     * ["id"] => "82"
     * ["remark"] => 'hello',
     * ["time"] => "2016-06-15 15:23:21",
     * ["contact__id"] => "182",
     * ["contact__name"] => "解然",
     * ["contact__phone"] =>"18888888888",
     * }
     *
     */
    public static function convert2DTo1D(&$originalArray, $convertNodeName = '')
    {
        if ($convertNodeName) {
            $node = $originalArray[$convertNodeName];
            $originalArray = self::convert2DNodeTo1D($originalArray, $convertNodeName, $node);
        } else {
            foreach ($originalArray as $oneKey => $oneValue) {
                self::convert2DNodeTo1D($originalArray, $oneKey, $oneValue);
            }
        }

        return $originalArray;
    }

    /**
     * @param $array
     * @param $nodeName
     * @param $node
     * @return mixed
     */
    private static function convert2DNodeTo1D(&$array, $nodeName, $node)
    {
        if ($node && is_array($node)) {
            foreach ($node as $k => $v) {
                if (empty($k)) {
                    break;
                } else {
                    $newKey = $nodeName . "__" . $k;
                    if (array_key_exists($newKey, $array)) {
                        $newKey = $newKey . "__";
                    }
                    $array[$newKey] = $v;
                }
            }
        }

        return $array;
    }

    /**
     * 友好的显示数据集信息
     *
     * @param array $dbSet
     *            数据集
     * @param array $mapArray
     *            转换数组（多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的枚举）,例如
     *            array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))
     * @param array $funcArray
     *            函数数组，多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的函数，
     *            此函数仅支持一个参数，即将数据库内的值作为本函数的参数
     *            1、如果是全局函数可以直接写函数的名称，
     *            2、如果是类的方法，请使用如下格式进行书写 nameSpace/className|methodName,
     *            其中如果是直接使用调用方类内的其他某个方法,nameSpace/className可以直接用__CLASS__表示
     *
     * @return array 友好显示的数据集信息
     */
    public static function friendlyDisplayDbSet(&$dbSet, $mapArray = null, $funcArray = null)
    {
        if ($dbSet === false || $dbSet === null) {
            return $dbSet;
        }

        $dbSet = (array)$dbSet;

        foreach ($dbSet as $key => $row) {
            self::friendlyDisplayEntity($dbSet[$key], $mapArray, $funcArray);
        }

        return $dbSet;
    }

    /**
     * 友好的显示数据集信息
     *
     * @param array $dataEntity
     *            数据实体
     * @param array $mapArray
     *            转换数组（多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的枚举）,例如
     *            array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))
     * @param array $funcArray
     *            函数数组，多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的函数，
     *            此函数仅支持一个参数，即将数据库内的值作为本函数的参数
     *            1、如果是全局函数可以直接写函数的名称，
     *            2、如果是类的方法，请使用如下格式进行书写 nameSpace/className|methodName,
     *            其中如果是直接使用调用方类内的其他某个方法,nameSpace/className可以直接用__CLASS__表示
     *
     * @return array 友好显示的数据实体
     */
    public static function friendlyDisplayEntity(&$dataEntity, $mapArray = null, $funcArray = null)
    {
        if ($dataEntity === false || $dataEntity === null) {
            return $dataEntity;
        }

        $dataEntity = (array)$dataEntity;

        if ($mapArray != null && is_array($mapArray)) {
            foreach ($mapArray as $col => $pair) {
                if (isset($dataEntity[$col])) {
                    if (isset($pair[$dataEntity[$col]])) {
                        $dataEntity[$col . '_text'] = $pair[$dataEntity[$col]];
                    } else {
                        $dataEntity[$col . '_text'] = $dataEntity[$col];
                    }
                }
            }
        }

        if ($funcArray != null && is_array($funcArray)) {
            foreach ($funcArray as $col => $func) {
                if (isset($row[$col])) {
                    if (strpos($func, '|') < 0) {
                        $result = call_user_func($func, $row[$col]);
                    } else {
                        $className = StringHelper::getStringBeforeSeperator($func, '|');
                        $methodName = StringHelper::getStringAfterSeperator($func, '|');

                        $result = ReflectionHelper::executeMethod($className, $methodName, null, array(
                            $row[$col]
                        ));
                    }

                    $dataEntity[$col . '_text'] = $result;
                }
            }
        }

        return $dataEntity;
    }

    /**
     * 返回数组的维度
     * @param  array $array [description]
     * @return int      [description]
     */
    public static function getLevel($array)
    {
        $levels = array(0);

        function analyse($arr, &$al, $level = 0)
        {
            if (is_array($arr)) {
                $level++;
                $al[] = $level;
                foreach ($arr as $v) {
                    analyse($v, $al, $level);
                }
            }
        }

        analyse($array, $levels);
        return max($levels);
    }

    /**
     * 合并array,如果有一个为null，则返回另外一个值（与array_merge的区别是，array_merge中如果有一个为null，则结果为null）
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function merge($array1, $array2)
    {
        if (empty($array1)) {
            return $array2;
        } else {
            if (empty($array2)) {
                return $array1;
            } else {
                return array_merge($array1, $array2);
            }
        }
    }
}