<?php

namespace Hiland\Utils\Data;

use ReflectionException;

class ArrayHelper
{
    /**
     * 判断一个项目是否存在array中。(其实是判断这个item的value是否存在于$array内)
     * @param $array array
     * @param $item  mixed
     * @return bool
     */
    public static function isContains($array, $item)
    {
        return in_array($item, $array);
    }

    /**
     * 判断某个键是否存在于某个数组内
     * @param $array
     * @param $key
     * @return bool
     */
    public static function isContainsKey($array, $key)
    {
        return array_key_exists($key, $array);
    }

    /**
     * 判断某个值是否存在于某个数组内
     * @param $array
     * @param $value
     * @return bool
     */
    public static function isContainsValue($array, $value){
        foreach ($array as $k=>$v){
            if($v== $value){
                return true;
            }
        }

        return false;
    }



    /**
     * 在数组的开头添加新的元素(新元素的key是数字形式索引)
     * @param $array
     * @param ...$items
     * @return mixed
     */
    public static function addHead($array, ...$items)
    {
        array_unshift($array, ...$items);
        return $array;
    }

    /**
     * 为数组添加Key/Value形式的item
     * @param $array
     * @param $key
     * @param $value
     * @return array|mixed
     */
    public static function addItem(&$array, $key, $value)
    {
        if ($array == null) {
            $array = [];
        }

        $array[$key] = $value;
        return $array;
    }

    /**
     * 在数组的末尾添加新的元素(新元素的key是数字形式索引)
     * 方法push和方法addTail功能相同,互为别名
     * @param $array
     * @param ...$items
     * @return array
     */
    public static function addTail($array, ...$items)
    {
        return self::push($array, ...$items);
    }

    /**
     * 在数组的末尾添加新的元素(新元素的key是数字形式索引)
     * 方法push和方法addTail功能相同,互为别名
     * @param array $array
     * @param mixed ...$items
     * @return array
     */
    public static function push(array $array, ...$items)
    {
        array_push($array, ...$items);
        return $array;
    }

    /**
     * 移除数组中的某个元素
     * @param $array
     * @param $item
     * @return array
     */
    public static function removeItem($array, $item)
    {
        if (ObjectHelper::getType($array) == ObjectTypes::ARRAYS) {
            if ($idx = array_search($item, $array, true)) {
                unset($array[$idx]);
            }
        }

        return $array;
    }

    public static function removeIndex($array, $index)
    {
        if (ObjectHelper::getType($array) == ObjectTypes::ARRAYS) {
            array_splice($array, $index, 1);
        }

        return $array;
    }

    /**
     * 删除数组的第一个元素
     * @param $array
     * @return mixed|null
     */
    public static function removeHead($array)
    {
        if (ObjectHelper::getType($array) == ObjectTypes::ARRAYS) {
            array_shift($array);
        }

        return $array;
    }

    /**
     * 删除数组的最后一个元素
     * @param $array
     * @return mixed|null
     */
    public static function removeTail($array)
    {
        if (ObjectHelper::getType($array) == ObjectTypes::ARRAYS) {
            $length = self::getLength($array);
            $lastIndex = $length - 1;
            array_splice($array, $lastIndex, 1);
        }

        return $array;
    }

    /**获取数组内元素的个数
     * @param $array
     * @return int
     */
    public static function getLength($array)
    {
        return count($array);
    }

    /**
     * 判断当前是否为关联数组
     * @param $array
     * @return bool
     */
    public static function isAssociateArray($array)
    {
        if (ObjectHelper::isEmpty($array)) {
            return false;
        }

        $result = array_keys($array);
        if ($result && isset($result[0])) {
            if (ObjectHelper::getType($result[0]) == ObjectTypes::STRING) {
                return true;
            }
        }

        return false;
    }

    /**
     * 判断当前是否为索引数组
     * @param $array
     * @return bool
     */
    public static function isIndexArray($array)
    {
        if (ObjectHelper::isEmpty($array)) {
            return false;
        }

        $result = array_keys($array);
        if ($result && isset($result[0])) {
            if (ObjectHelper::getType($result[0]) == ObjectTypes::INTEGER) {
                return true;
            }
        }

        return false;
    }

    /**
     * 数组转简单对象
     * @param array $array 名值对类型的一维或者多维数组
     * @return object
     */
    public static function convertToObject($array)
    {
        return ObjectHelper::convertFromArray($array);
    }

    /**
     * @param $object
     * @return mixed
     */
    public static function convertFromObject($object)
    {
        return ObjectHelper::convertTOArray($object);
    }

    /**
     * 将xml转为array
     * @param string $xml xml字符串
     * @return array    转换得到的数组
     */
    public static function convertFromXml($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 将数组转变为xml数据
     * @param array  $array         有名值对类型的数组
     * @param string $charset       xml数据的编码（缺省情况下为utf-8）
     * @param string $rootName      xml数据的跟节点名称
     * @param bool   $includeHeader 是否在生成的xml文档中包含xml头部声明
     * @return string
     */
    public static function convertToXml($array, $rootName = 'myXml', $includeHeader = true, $charset = 'utf8')
    {
        $xml = '';
        if ($includeHeader) {
            $xml .= '<?xml version="1.0" encoding="' . $charset . '" ?>';
        }
        $xml .= "<$rootName>";
        $xml .= self::toXmlInner($array);
        $xml .= "</$rootName>";
        return $xml;
    }

    /**
     * @param $value
     * @return string
     */
    private static function toXmlInner($value)
    {
        $xml = '';
        if ((!is_array($value) && !is_object($value)) || count($value) <= 0) {
            // do nothing;
        } else {
            foreach ($value as $key => $val) {
                if (is_array($val) || is_object($val)) { // 判断是否是数组，或者，对像
                    $xml .= "<" . $key . ">";
                    $xml .= self::toXmlInner($val); // 是数组或者对像就递归调用
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
     * @param array $originalArray 有简单值构成key和value的名值对一维数组
     * @return array
     */
    public static function exchangeKeyValue($originalArray)
    {
        return array_flip($originalArray);
    }

    /**
     * 从(类似表结构的)二维数组中抽取信息转换为一维数组
     * @param array  $originalArray
     *            原始的二维数组
     * @param string $newKeyName
     *            二维数组中的某个元素的名称，其对应的值将作为一维数组的key
     *            如果这个值为空，那么将会把一维数组的key作为此值
     * @param string $newValueName
     *            二维数组中的某个元素的名称，其对应的值将作为一维数组的value
     *            如果这个值为空，那么将会把一维数组的key作为此值
     * @return array 转换后的一维数组
     * @example 待转换的有别名的二维数组类似如下：
     *            $originalArray => array(
     *            'UNOUT' => array(
     *            'value' => 0,
     *            'display' => '未出局'
     *            ),
     *            'PARTIALOUTASLOCK' => array(
     *            'value' => 1,
     *            'display' => '锁定未出局'
     *            ),
     *            'OUT' => array(
     *            'value' => 10,
     *            'display' => '出局'
     *            )
     *            )
     *            1、 extract2DTo1D($originalArray, 'value', 'display')的结果为
     *            array(3) {
     *            [0] => string(9) "未出局"
     *            [1] => string(15) "锁定未出局"
     *            [10] => string(6) "出局"
     *            }
     *            2、 extract2DTo1D($originalArray, 'value')的结果为
     *            array(3) {
     *            [0] => string(5) "UNOUT"
     *            [1] => string(16) "PARTIALOUTASLOCK"
     *            [10] => string(3) "OUT"
     *            }
     *            3、 extract2DTo1D($originalArray,'','display')的结果为
     *            array(3) {
     *            ["UNOUT"] => string(9) "未出局"
     *            ["PARTIALOUTASLOCK"] => string(15) "锁定未出局"
     *            ["OUT"] => string(6) "出局"
     *            }
     *            4、 extract2DTo1D($originalArray)的结果为
     *            array(3) {
     *            ["UNOUT"] => string(5) "UNOUT"
     *            ["PARTIALOUTASLOCK"] => string(16) "PARTIALOUTASLOCK"
     *            ["OUT"] => string(3) "OUT"
     *            }
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
     * 友好地显示数据集信息
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
     * @return array 友好显示的数据集信息
     * @throws ReflectionException
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
     * 友好地显示数据集信息
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
     * @return array 友好显示的数据实体
     * @throws ReflectionException
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
                        $className = StringHelper::getStringBeforeSeparator($func, '|');
                        $methodName = StringHelper::getStringAfterSeparator($func, '|');

                        $result = ReflectionHelper::executeInstanceMethod($className, $methodName, null, null, array(
                            $row[$col],
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
     * @param array $array [description]
     * @return int      [description]
     */
    public static function getLevel($array)
    {
        // scalar value has depth 0
        if (!is_array($array)) return 0;

        // array has min depth of 1
        $depth = 1;
        foreach ($array as $element) {
            if (is_array($element)) {
                // is the sub array deeper than already known?
                $sub_depth = self::getLevel($element);
                if ($sub_depth >= $depth) {
                    $depth += $sub_depth;
                }
            }
        }
        return $depth;
    }

    /**
     * 合并array,如果有一个为null，则返回另外一个值（与array_merge的区别是，array_merge中如果有一个为null，则结果为null）
     * @param mixed ...$arrays
     * @return array
     */
    public static function merge(...$arrays)
    {
        $targetArrays = [];
        foreach ($arrays as $item) {
            if ($item) {
                array_push($targetArrays, $item);
            }
        }

        return array_merge(...$targetArrays);
    }

    /** 在二维数组中，根据某一个维度的名称进行排序
     * @param array  $array      目标数组
     * @param string $columnName 目标维度名称
     * @param int    $sortType   排序类型 SORT_ASC 或者 SORT_DESC
     * @return array
     * @example 对类似如下数组中，根据“2010年”年排序
     *                           $myArray = [
     *                           [
     *                           "地区" => "河南",
     *                           "2010年" => 5437.1,
     *                           "2011年" => 5542.5,
     *                           "2012年" => 5638.6,
     *                           ],
     *                           [
     *                           "地区" => "黑龙江",
     *                           "2010年" => 5012.8,
     *                           "2011年" => 5570.6,
     *                           "2012年" => 5761.5,
     *                           ],
     *                           [
     *                           "地区" => "山东",
     *                           "2010年" => 4335.7,
     *                           "2011年" => 4426.3,
     *                           "2012年" => 4511.4,
     *                           ],
     *                           ];
     *                           $actual = ArrayHelper::sort2D($myArray, "2011年");
     *                           var_dump($actual);
     */
    public static function sort2D($array, $columnName, $sortType = SORT_ASC)
    {
        array_multisort(array_column($array, $columnName), $sortType, $array);
        return $array;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     * @param array $arrayData
     * @return array
     */
    public static function divide($arrayData)
    {
        return [array_keys($arrayData), array_values($arrayData)];
    }

    /**
     * Select方法的别名,Laravel内相同功能的方法名称就为pluck.
     * 像CSS选择器一样,从多维数组内获取符合XPath的信息
     * ════════════════════════
     * @param array  $arrayData
     * @param string $selector
     * @param bool   $withIndexKey 查询路径里面是否包含索引数组的数字key,默认false
     * @return array
     * @example
     *                             1、目标数组 $array = [
     *                             ['website' => ['id' => 1, 'url' => 'reddit.com']],
     *                             ['website' => ['id' => 2, 'url' => 'twitter.com']],
     *                             ['website' => ['id' => 3, 'url' => 'dev.to']],
     *                             ];
     *                             2、应用xpath选取数据
     *                             $names = ArrayHelper::select($array, 'website.url');
     *                             3、--output--
     *                             ['reddit.com', 'twitter.com', 'dev.to']
     */
    public static function pluck($arrayData, $selector, $withIndexKey = false)
    {
        return self::select($arrayData, $selector, $withIndexKey);
    }

    /**
     * 像CSS选择器一样,从多维数组内获取符合XPath的信息
     * ════════════════════════
     * @param array  $arrayData
     * @param string $selector
     * @param bool   $withIndexKey 查询路径里面是否包含索引数组的数字key,默认false
     * @return array
     * @example
     *                             1、目标数组 $array = [
     *                             ['website' => ['id' => 1, 'url' => 'reddit.com']],
     *                             ['website' => ['id' => 2, 'url' => 'twitter.com']],
     *                             ['website' => ['id' => 3, 'url' => 'dev.to']],
     *                             ];
     *                             2、应用xpath选取数据
     *                             $names = ArrayHelper::select($array, 'website.url');
     *                             3、--output--
     *                             ['reddit.com', 'twitter.com', 'dev.to']
     */
    public static function select($arrayData, $selector, $withIndexKey = false)
    {
        if ($withIndexKey) {
            $indexKeyPrefix = "";
        } else {
            $indexKeyPrefix = "I_I_";
        }

        $flattenArray = self::flatten($arrayData, ".", "", $indexKeyPrefix);
        $keys = array_keys($flattenArray);
        $values = array_values($flattenArray);

        $result = [];
        for ($i = 0; $i < self::getLength($keys); $i++) {
            $currentKey = $keys[$i];
            if (!$withIndexKey) {
                $currentKey = StringHelper::replace($currentKey, "/$indexKeyPrefix\d*\./", "", true);
            }

            if ($selector == $currentKey) {
                $result[] = $values[$i];
            }
        }

        return $result;
    }

    /**
     * 将多维数组平面化
     * @param iterable $arrayData      待转换的多维数组
     * @param string   $separator      平面化后各个维度Key之间的分隔符,缺省为"."
     * @param string   $prepend        平面化后的key前缀,缺省为空
     * @param string   $indexKeyPrefix 如果是索引性质的数组,想给索引key加一个前缀的名称,缺省为空
     * @return array
     */
    public static function flatten($arrayData, $separator = ".", $prepend = '', $indexKeyPrefix = "")
    {
        $results = [];

        foreach ($arrayData as $key => $value) {
            if (ObjectHelper::getType($key) == ObjectTypes::INTEGER) {
                $key = $indexKeyPrefix . $key;
            }

            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::flatten($value, $separator, $prepend . $key . $separator, $indexKeyPrefix));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * 分别提取每个数组的第n个元素组成新的二维数组(内层纬度为每个传入数组的第n个元素组成的数组；外层纬度为传入的最短数组的长度)
     * @param ...$arrayData
     * @return array
     * @example
     *         $a = [1,3,5,7,9];
     *         $b= [2,4,6,8];
     *         $c= ["a","b","c"];
     *         $result= ArrayHelper::zip($a,$b,$c);
     *         ────────────────────────
     *         $result 的值为 [[1,2,"a"],[3,4,"b"],[5,6,"c"]];
     */
    public static function zip(...$arrayData)
    {
        $arraysCount = count($arrayData);
        $arrayLength = array_map(function ($item) {
            return count($item);
        }, $arrayData);

        $arrayLengthMin = min($arrayLength);
        $result = [];
        for ($i = 0; $i < $arrayLengthMin; $i++) {
            $indexArray = [];
            for ($j = 0; $j < $arraysCount; $j++) {
                $indexArray[] = $arrayData[$j][$i];
            }
            $result[] = $indexArray;
        }

        return $result;
    }
}