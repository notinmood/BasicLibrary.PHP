<?php

namespace Hiland\Utils\Data;

class ObjectHelper
{
    /**
     * 将带有名值对类型数组的各成员，赋值给复杂对象的属性上
     * 如果对象已经拥有该属性，那么数组成员的值将会覆盖对象原有的属性值
     * 如果对象没有改属性，那么将会为对象创建改属性，并赋数组成员的的值
     *
     * @param array $array
     *            名值对类型的原数组
     * @param object $object
     *            目标对象
     * @return object 赋值后的对象
     */
    public static function arrayToComplexOjbect($array, $object)
    {
        foreach ($array as $k => $v) {
            $object->$k = $v;
        }

        return $object;
    }

    /**
     * 数组转简单对象
     *
     * @param array $array 名值对类型的一维或者多维数组
     * @return object
     */
    public static function arrayToObject($array)
    {
        $json = json_encode($array);
        return json_decode($json);
    }

    /**
     * 对象转简单数组
     *
     * @param object $object
     * @return mixed
     */
    public static function objectToArray($object)
    {
        $json = json_encode($object);
        return json_decode($json, true);
    }

    /**
     * 根据变量的值查找变量名字
     *
     * @param mixed $var
     *            变量的值
     * @param mixed $scope
     *            查找范围,默认全局查找。
     *            如果是在方法外，查找访问内的变量，
     *            此scope一定要设置为get_defined_vars()
     *            PHP中，所有的变量都存储在"符号表"的HastTable结构中，
     *            符号的作用域是与活动符号表相关联的。因此，同一时间，只有一个活动符号表。
     *            要获取到当前活动符号表可以通过 get_defined_vars 方法来获取。
     *            http://blog.csdn.net/fdipzone/article/details/14643331
     * @return mixed
     */
    public static function getVarName(&$var, $scope = null)
    {
        // 如果没有范围则在globals中找寻
        if (empty($scope)) {
            $scope = $GLOBALS;
        }

        // 因有可能有相同值的变量,因此先将当前变量的值保存到一个临时变量中,
        // 然后再对原变量赋唯一值,以便查找出变量的名称,找到名字后,
        // 将临时变量的值重新赋值到原变量
        $tmp = $var;

        $var = 'tmp_value_' . mt_rand();
        $name = array_search($var, $scope, true); // 根据值查找变量名称

        $var = $tmp;
        return $name;
    }

    /**
     *判断两个值是否相等
     * @param $dataA
     * @param $dataB
     * @param bool $strictlyCompare 是否进行严格比较（严格模式是先比较类型，再比较值；非严格模式下 trure和“true”是相等的）
     * @return bool
     */
    public static function equal($dataA, $dataB, $strictlyCompare = false)
    {
        $typeA = self::getType($dataA);
        $typeB = self::getType($dataB);

        if ($strictlyCompare) {
            if ($typeA != $typeB) {
                return false;
            } else {
                if ($dataA == $dataB) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            $convertedDataA = self::getString($dataA);
            $convertedDataB = self::getString($dataB);

            if ($convertedDataA == $convertedDataB) {
                return true;
            } else {
                return false;
            }
        }

    }

    /**
     * 判断数据类型（由于php本身的gettype函数有可能改变，此处使用自定义的函数进行判断）
     * @param $data
     * @return string
     */
    public static function getType($data)
    {
        if (is_bool($data)) {
            return ObjectTypes::BOOLEAN;
        }

        if (is_string($data)) {
            return ObjectTypes::STRING;
        }

        if (is_int($data)) {
            return ObjectTypes::INTEGER;
        }

        if (is_array($data)) {
            return ObjectTypes::ARRAYS;
        }

        if (is_float($data)) {
            return ObjectTypes::FLOAT;
        }

        if (is_double($data)) {
            return ObjectTypes::DOUBLE;
        }

        if (is_null($data)) {
            return ObjectTypes::NULL;
        }

        if (is_resource($data)) {
            return ObjectTypes::RESOURCE;
        }

        if (is_object($data)) {
            return ObjectTypes::OBJECT;
        }
    }

    public static function getString($data)
    {
        $type = self::gettype($data);
        switch ($type) {
            case ObjectTypes::BOOLEAN:
                if ($data == true) {
                    $result = 'true';
                } else {
                    $result = 'false';
                }
                break;
            case ObjectTypes::ARRAYS:
            case ObjectTypes::OBJECT:
                $result = json_encode($data);
                break;
            case ObjectTypes::NULL:
                $result = '';
                break;
            default:
                $result = (string)$data;
        }

        return $result;
    }
}