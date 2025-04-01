<?php

namespace Hiland\Data;

class ArrayHelper
{
    /**
     * 判断一个项目是否存在array中。(其实是判断这个item的value是否存在于$array内，不管key是否存在。)
     * (本方法和isContainsValue方法功能相同,互为别名)
     * @param $arrayData array
     * @param $item      mixed
     * @return bool
     */
    public static function isContains(array $arrayData, mixed $item): bool
    {
        return in_array($item, $arrayData, true);
    }

    /**
     * 判断某个键是否存在于某个数组内
     * @param $array
     * @param $key
     * @return bool
     */
    public static function isContainsKey($array, $key): bool
    {
        return array_key_exists($key, $array);
    }

    /**
     * 判断某个值是否存在于某个数组内
     * (本方法和isContains方法功能相同,互为别名)
     * @param $arrayData
     * @param $value
     * @return bool
     */
    public static function isContainsValue($arrayData, $value): bool
    {
        return in_array($value, $arrayData, true);
    }

    /**
     * 在数组的开头添加新的元素(新元素的 key 是数字形式索引)
     * @param $array
     * @param ...$items
     * @return mixed
     */
    public static function addHead($array, ...$items): mixed
    {
        array_unshift($array, ...$items);
        return $array;
    }

    /**
     * 为数组添加 Key/Value 形式的 item
     * @param $array
     * @param $key
     * @param $value
     * @return array|mixed
     */
    public static function addItem($array, $key, $value): mixed
    {
        if ($array === null) {
            $array = [];
        }

        $array[$key] = $value;
        return $array;
    }

    /**
     * 在数组的末尾添加新的元素(新元素的 key 是数字形式索引)
     * 方法 push 和方法 addTail 功能相同,互为别名
     * @param $array
     * @param ...$items
     * @return array
     */
    public static function addTail($array, ...$items): array
    {
        return self::push($array, ...$items);
    }

    /**
     * 安全地从数组中获取元素(即便没有这个元素也不会抛出异常)
     * @param $array
     * @param $key
     * @param $defaultValue
     * @return mixed|null
     */
    public static function getItem($array, $key, $defaultValue = null): mixed
    {
        return $array[$key] ?? $defaultValue;
    }

    /**
     * 获取item在索引数组的索引位置或者关联数组的键值
     * @param $array
     * @param $item
     * @return int|string
     */
    public static function getIndex($array, $item): int|string
    {
        $index = array_search($item, $array, true);
        if ($index === false) {
            return -1;
        }

        return $index;
    }

    /**
     * 在数组的末尾添加新的元素(新元素的key是数字形式索引)
     * 方法 push 和方法 addTail 功能相同,互为别名
     * @param array $array
     * @param mixed ...$items
     * @return array
     */
    public static function push(array $array, ...$items): array
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
    public static function removeItem($array, $item): array
    {
        if (ObjectHelper::getTypeName($array) === ObjectTypes::ARRAY) {
            if (self::isContains($array, $item)) {
                $idx = array_search($item, $array, true);
                unset($array[$idx]);
            }
        }

        return $array;
    }

    /**
     * @param $array
     * @param $index
     * @return mixed
     */
    public static function removeIndex($array, $index): mixed
    {
        if (ObjectHelper::getTypeName($array) === ObjectTypes::ARRAY) {
            array_splice($array, $index, 1);
        }

        return $array;
    }

    /**
     * 删除数组的第一个元素
     * @param $array
     * @return mixed|null
     */
    public static function removeHead($array): mixed
    {
        if (ObjectHelper::getTypeName($array) === ObjectTypes::ARRAY) {
            array_shift($array);
        }

        return $array;
    }

    /**
     * 删除数组的最后一个元素
     * @param $array
     * @return mixed|null
     */
    public static function removeTail($array): mixed
    {
        if (ObjectHelper::getTypeName($array) === ObjectTypes::ARRAY) {
            $length    = self::getLength($array);
            $lastIndex = $length - 1;
            array_splice($array, $lastIndex, 1);
        }

        return $array;
    }

    /**
     * 获取数组内元素的个数
     * @param $array
     * @return int
     */
    public static function getLength($array): int
    {
        return count($array);
    }

    /**
     * 判断当前是否为关联数组
     * @param $array
     * @return bool
     */
    public static function isAssociateArray($array): bool
    {
        if (ObjectHelper::isEmpty($array)) {
            return false;
        }

        return key($array) !== 0;
    }

    /**
     * 判断当前是否为索引数组
     * @param $array
     * @return bool
     */
    public static function isIndexArray($array): bool
    {
        if (ObjectHelper::isEmpty($array)) {
            return false;
        }

        if (key($array) === 0) {
            return true;
        }

        return false;
    }

    /**
     * 数组转简单对象
     * @param array $array 名值对类型的一维或者多维数组
     * @return object
     */
    public static function convertToObject(array $array): object
    {
        return ObjectHelper::convertFromArray($array);
    }

    /**
     * @param $object
     * @return mixed
     */
    public static function convertFromObject($object): mixed
    {
        return ObjectHelper::convertTOArray($object);
    }

    /**
     * 将xml转为array
     * @param string $xml xml字符串
     * @return array    转换得到的数组
     */
    public static function convertFromXml(string $xml): array
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 将数组转变为xml数据
     * @param array $array 有名值对类型的数组
     * @param string $charset xml数据的编码（缺省情况下为utf-8）
     * @param string $rootName xml数据的跟节点名称
     * @param bool $includeHeader 是否在生成的xml文档中包含xml头部声明
     * @return string
     */
    public static function convertToXml(array $array, string $rootName = 'myXml', bool $includeHeader = true, string $charset = 'utf8'): string
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
    private static function toXmlInner($value): string
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
    public static function exchangeKeyValue(array $originalArray): array
    {
        return array_flip($originalArray);
    }

    /**
     * 从(类似表结构的)二维数组中抽取信息转换为一维数组
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
    public static function extract2DTo1D(array $originalArray, string $newKeyName = '', string $newValueName = ''): array
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
     * @param array|null $mapArray
     *            转换数组（多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的枚举）,例如
     *            array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))
     * @param array|null $funcArray
     *            函数数组，多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的函数，
     *            此函数仅支持一个参数，即将数据库内的值作为本函数的参数
     *            1、如果是全局函数可以直接写函数的名称，
     *            2、如果是类的方法，请使用如下格式进行书写 nameSpace/className|methodName,
     *            其中如果是直接使用调用方类内的其他某个方法,nameSpace/className可以直接用__CLASS__表示
     * @return array|null 友好显示的数据集信息
     */
    public static function displayDbSetFriendly(array &$dbSet, array $mapArray = null, array $funcArray = null): ?array
    {
        if (!$dbSet || $dbSet === null) {
            return $dbSet;
        }

        foreach ($dbSet as $key => $row) {
            self::displayEntityFriendly($dbSet[$key], $mapArray, $funcArray);
        }

        return $dbSet;
    }

    /**
     * 友好地显示数据集信息
     * @param array $dataEntity
     *            数据实体
     * @param array|null $mapArray
     *            转换数组（多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的枚举）,例如
     *            array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))
     * @param array|null $funcArray
     *            函数数组，多维数组，第一维度表示要匹配的数据集字段名称，
     *            第二维度是对本字段的取值进行友好显示的函数，
     *            此函数仅支持一个参数，即将数据库内的值作为本函数的参数
     *            1、如果是全局函数可以直接写函数的名称，
     *            2、如果是类的方法，请使用如下格式进行书写 nameSpace/className|methodName,
     *            其中如果是直接使用调用方类内的其他某个方法,nameSpace/className可以直接用__CLASS__表示
     * @return array|null 友好显示的数据实体
     */
    public static function displayEntityFriendly(array &$dataEntity, array $mapArray = null, array $funcArray = null): ?array
    {
        if (!$dataEntity || $dataEntity === null) {
            return $dataEntity;
        }

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

        if ($funcArray !== null && is_array($funcArray)) {
            foreach ($funcArray as $col => $func) {
                if (isset($row[$col])) {
                    if (strpos($func, '|') < 0) {
                        $result = $func($row[$col]);
                    } else {
                        $className  = StringHelper::getStringBeforeSeparator($func, '|');
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
    public static function getLevel(array $array): int
    {
        // scalar value has depth 0
        if (!is_array($array)) {
            return 0;
        }

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
    public static function merge(...$arrays): array
    {
        $targetArrays = [];
        foreach ($arrays as $item) {
            if ($item) {
                $targetArrays[] = $item;
            }
        }

        return array_merge(...$targetArrays);
    }

    /** 在二维数组中，根据某一个维度的名称进行排序
     * @param array $array 目标数组
     * @param string $columnName 目标维度名称
     * @param int $sortType 排序类型 SORT_ASC 或者 SORT_DESC
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
    public static function sort2D(array $array, string $columnName, int $sortType = SORT_ASC): array
    {
        array_multisort(array_column($array, $columnName), $sortType, $array);
        return $array;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     * @param array $arrayData
     * @return array
     */
    public static function divide(array $arrayData): array
    {
        return [array_keys($arrayData), array_values($arrayData)];
    }

    /**
     * Select方法的别名,Laravel内相同功能的方法名称就为pluck.
     * 像CSS选择器一样,从多维数组内获取符合XPath的信息
     * ════════════════════════
     * @param array $arrayData
     * @param string $selector
     * @param bool $withIndexKey 查询路径里面是否包含索引数组的数字key,默认false
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
    public static function pluck(array $arrayData, string $selector, bool $withIndexKey = false): array
    {
        return self::select($arrayData, $selector, $withIndexKey);
    }

    /**
     * 像CSS选择器一样,从多维数组内获取符合XPath的信息
     * ════════════════════════
     * @param array $arrayData
     * @param string $selector
     * @param bool $withIndexKey 查询路径里面是否包含索引数组的数字key,默认false
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
    public static function select(array $arrayData, string $selector, bool $withIndexKey = false): array
    {
        if ($withIndexKey) {
            $indexKeyPrefix = "";
        } else {
            $indexKeyPrefix = "I_I_";
        }

        $flattenArray = self::flatten($arrayData, ".", "", $indexKeyPrefix);
        $keys         = array_keys($flattenArray);
        $values       = array_values($flattenArray);

        $result = [];
        for ($i = 0; $i < self::getLength($keys); $i++) {
            $currentKey = $keys[$i];
            if (!$withIndexKey) {
                $currentKey = StringHelper::replace($currentKey, "/$indexKeyPrefix\d*\./", "", true);
            }

            if ($selector === $currentKey) {
                $result[] = $values[$i];
            }
        }

        return $result;
    }


    /**
     * 按照节点的成员信息进行过滤
     * @param $array
     * @param string $memberName
     * @param mixed $memberValue
     * @return array
     */
    public static function filterByNodeMember($array, string $memberName = '', $memberValue = null): array
    {
        $result = [];
        //TODO:xiedali@2025/02/28 待完成。

        return $result;
    }

    /**
     * 获取节点的值
     * (结果可能为一个子数组，也可以为一个具体数值)
     * @param array $arrayData
     * @param string $key 节点的名称(可以包含多级别的名称，多级别间用 "." 连接)
     * @param mixed|null $defaultValue
     * @return mixed|null|array
     */
    public static function getNode(array $arrayData, string $key, mixed $defaultValue = null): mixed
    {
        $keyNodes = explode(".", $key);

        $configContent = $arrayData;
        if (!$configContent) {
            return $defaultValue;
        }

        $firstNodeName = $keyNodes[0];

        $result       = self::getItem($configContent, $firstNodeName);
        $keyNodeCount = count($keyNodes);

        for ($i = 1; $i < $keyNodeCount; $i++) {
            $currentNameNode = $keyNodes[$i];
            if (isset($result[$currentNameNode])) {
                $result = $result[$currentNameNode];
            } else {
                $result = $defaultValue;
                break;
            }
        }

        return $result;
    }

    /**
     * 将多维数组平面化
     * @param iterable $arrayData 待转换的多维数组
     * @param string $separator 平面化后各个维度Key之间的分隔符,缺省为"."
     * @param string $prepend 平面化后的key前缀,缺省为空
     * @param string $indexKeyPrefix 如果是索引性质的数组,想给索引key加一个前缀的名称,缺省为空
     * @return array
     */
    public static function flatten(iterable $arrayData, string $separator = ".", string $prepend = '', string $indexKeyPrefix = ""): array
    {
        $results = [];

        foreach ($arrayData as $key => $value) {
            if (ObjectHelper::getTypeName($key) === ObjectTypes::INTEGER) {
                $key = $indexKeyPrefix . $key;
            }

            if (is_array($value) && !empty($value)) {
                $results += static::flatten($value, $separator, $prepend . $key . $separator, $indexKeyPrefix);
                //$results = array_merge($results, static::flatten($value, $separator, $prepend . $key . $separator, $indexKeyPrefix));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * 分别提取每个数组的第n个元素组成新的二维数组(内层维度为每个传入数组的第n个元素组成的数组；外层维度为传入的最短数组的长度)
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
    public static function zip(...$arrayData): array
    {
        $arraysCount = count($arrayData);
        $arrayLength = array_map(static function ($item) {
            return count($item);
        }, $arrayData);

        $arrayLengthMin = min($arrayLength);
        $result         = [];
        for ($i = 0; $i < $arrayLengthMin; $i++) {
            $indexArray = [];
            for ($j = 0; $j < $arraysCount; $j++) {
                $indexArray[] = $arrayData[$j][$i];
            }
            $result[] = $indexArray;
        }

        return $result;
    }

    /**
     * 计算多个数组的笛卡尔积
     * @param array ...$arrays
     * @return array
     */
    public static function product(array ...$arrays): array {
        $result = [[]];
        foreach ($arrays as $array) {
            $temp = [];
            foreach ($result as $product) {
                foreach ($array as $item) {
                    $temp[] = array_merge($product, [$item]);
                }
            }
            $result = $temp;
        }
        return $result;
    }

    /**
     * 计算移动平均值
     * @param int[]|float[] $arrayData 待计算的数组
     * @param int $windowSize 窗口大小
     * @return array 移动平均值数组
     */
    public static function getMovingAverage(array $arrayData, int $windowSize): array
    {
        return NumberHelper::getMovingAverage($arrayData, $windowSize);
    }
}
