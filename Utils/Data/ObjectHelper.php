<?php

namespace Hiland\Utils\Data;

use DateTime;
use Exception;
use stdClass;

class ObjectHelper
{
    /**
     * 将带有名值对类型数组的各成员，赋值给复杂对象的属性上
     * 如果对象已经拥有该属性，那么数组成员的值将会覆盖对象原有的属性值
     * 如果对象没有该属性，那么将会为对象创建改属性，并赋数组成员的的值
     * @param array  $array
     *            名值对类型的原数组
     * @param object $object
     *            目标对象
     * @return object 赋值后的对象
     */
    public static function appendArrayToObject($array, $object = null)
    {
        if ($object == null) {
            $object = new stdClass();
        }

        foreach ($array as $k => $v) {
            $object->$k = $v;
        }

        return $object;
    }

    /**
     * 数组转简单对象
     * @param array $array 名值对类型的一维或者多维数组
     * @return object
     */
    public static function convertFromArray($array)
    {
        $json = json_encode($array);
        return json_decode($json);
    }

    /**
     * 对象转简单数组(跟get_object_vars相同功能)
     * @param object $object
     * @return mixed
     */
    public static function convertTOArray($object)
    {
        $json = json_encode($object);
        return json_decode($json, true);
    }

    /**
     * 根据变量的值查找变量名字
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
     * @return false|int|string
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
     * @param      $dataA
     * @param      $dataB
     * @param bool $strictlyCompare 是否进行严格比较（严格模式是先比较类型，再比较值；非严格模式下 trure和“true”是相等的）
     * @return bool
     */
    public static function equals($dataA, $dataB, $strictlyCompare = false)
    {
        $typeA = self::getTypeName($dataA);
        $typeB = self::getTypeName($dataB);

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
    public static function getTypeName($data)
    {
        if ($data instanceof DateTime) {
            return ObjectTypes::DATETIME;
        }

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
        $type = self::getTypeName($data);
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

    public static function isJson($data)
    {
        if (self::getTypeName($data) == ObjectTypes::STRING) {
            json_decode($data);
            return (json_last_error() == JSON_ERROR_NONE);
        } else {
            return false;
        }
    }

    /**判断一个对象是否存在
     * @param $data
     * @return bool
     */
    public static function isExist($data)
    {
        return !self::isEmpty($data);
    }

    /**
     * 判断一个对象是否为空判断一个对象是否为空。
     *以下的东西被认为是空的：
     * "" (空字符串)
     * 0 (作为整数的0)
     * 0.0 (作为浮点数的0)
     * "0" (作为字符串的0)
     * NULL
     * FALSE
     * array() (一个空数组)
     * $var; (一个声明了，但是没有值的变量)
     * 另外 一个没有内部成员的对象Object也是空的
     * @param      $data
     * @param null $memberName
     * @return bool
     */
    public static function isEmpty($data, $memberName = null)
    {
        if (!isset($data)) {
            return true;
        }

        if ($data == null) {
            return true;
        }

        $type = self::getTypeName($data);

        $result = false;
        if ($memberName) {
            $isMember = self::isMember($data, $memberName);
            if ($isMember) {
                $memberValue = self::getMember($data, $memberName);
                if (self::isEmpty($memberValue)) {
                    $result = true;
                } else {
                    $result = false;
                }
            } else {
                $result = true;
            }
        } else {
            switch ($type) {
                case ObjectTypes::OBJECT:
                    $emptyObject = new stdClass();
                    if ($data == $emptyObject) {
                        $result = true;
                    }
                    break;
                default:
                    $result = empty($data);
            }
        }

        return $result;
    }

    public static function isNull($data){
        return is_null($data);
    }

    public static function isNotNull($data){
        return !is_null($data);
    }

    /**
     * 判断一个数据是否为数值类型
     * ════════════════════════
     * 跟系统内置的is_numeric()不同点：
     * 字符串表示的数字，比如"123"(用引号包裹起来的数字)
     *  在is_numeric内是 true
     *  在本函数内是false
     * @param $data
     * @return bool
     */
    public static function isNumber($data)
    {
        // return is_numeric($data);

        $type = self::getTypeName($data);
        if ($type == ObjectTypes::INTEGER || $type == ObjectTypes::DOUBLE || $type == ObjectTypes::FLOAT) {
            return true;
        } else {
            return false;
        }
    }

    /**判断一个成员是否属于某个对象
     * @param $targetObject
     * @param $memberName
     * @return bool
     */
    public static function isMember($targetObject, $memberName)
    {
        $result = false;
        $type = self::getTypeName($targetObject);
        switch ($type) {
            case ObjectTypes::ARRAYS:
                $result = array_key_exists($memberName, $targetObject);
                break;
            case ObjectTypes::OBJECT:
                $result = property_exists($targetObject, $memberName);
                break;
            default:
                $result = false;
        }

        return $result;
    }

    public static function getMember($targetObject, $memberName, $defaultValue = null)
    {
        if ($targetObject) {
            if (self::isMember($targetObject, $memberName)) {
                $type = self::getTypeName($targetObject);
                switch ($type) {
                    case ObjectTypes::ARRAYS:
                        $result = $targetObject[$memberName];
                        break;
                    case ObjectTypes::OBJECT:
                        $result = $targetObject->$memberName;
                        break;
                    default:
                        $result = $defaultValue;
                }

                return $result;
            } else {
                return $defaultValue;
            }
        } else {
            return $defaultValue;
        }
    }

    /**
     * 获取对象对应的类型
     * @param $object
     * @return string|null 得到的是一个Class的完全名称(即包括命名空间在内的名称)
     */
    public static function getClassName($object)
    {
        try {
            $result = get_class($object);
        } catch (Exception $e) {
            $result = null;
        }

        return $result;
    }

    /**判断某个对象是否为某个类型的实例
     * 跟 运算符 a instanceof B 效果相同
     * @param $entity
     * @param $classFullName string 带命名空间的类型名称全名（调用的时候，获取某个类型的全名称可以使用::class关键字，即AAA::class）
     * @return bool
     * @example
     *                       ObjectHelper::isInstance($entity1,ActiveCode::class);
     */
    public static function isInstance($entity, $classFullName)
    {
        return is_a($entity, $classFullName);
    }

    /**
     * 获取给定对象的元素长度（目前仅支持字符串和数组的长度求取）
     * @param $data
     * @return false|int
     */
    public static function getLength($data)
    {
        $type = self::getTypeName($data);
        $result = 0;

        switch ($type) {
            case ObjectTypes::ARRAYS:
                $result = ArrayHelper::getLength($data);
                break;
            case ObjectTypes::STRING:
                $result = StringHelper::getLength($data);
                break;
            default:
                $result = 0;
        }

        return $result;
    }
}
