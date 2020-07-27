<?php

namespace Hiland\Utils\Data;

class ReflectionHelper
{
    /**
     * 通过反射创建对象实例
     *
     * @param string $className
     *            类名称字符串（如果有命名空间的话带命名空间，例如Tencent\Model\Foo2）
     * @param array $args
     * @return object
     */
    public static function createInstance($className, array $args = null)
    {
        if (empty($args)) {
            return new $className();
        } else {
            $class = self::getReflectionClass($className, $args);
            return $class->newInstanceArgs((array)$args);
        }
    }

    /**
     * 获取类型的反射信息
     * @param string $className 类名称（如果有命名空间，请携带命名空间，如：'Tencent\Model\Bar'）
     * @param array $args 类构造器参数数组
     * @return \ReflectionClass
     */
    public static function getReflectionClass($className, &$args = null)
    {
        $refClass = new \ReflectionClass($className);
        if (isset($args) && !isset($args[0])) {
            $args2 = array();
            foreach ($refClass->getMethod('__construct')->getParameters() as $param) {
                if (isset($args[$param->name])) {
                    $args2[] = $args[$param->name];
                } elseif ($param->isDefaultValueAvailable()) {
                    $args2[] = $param->getDefaultValue();
                }
            }
            $args = $args2;
        }

        return $refClass;
    }

    /**
     * 执行某个类里面的方法
     * @param string $className 类名称（如果有命名空间，请携带命名空间，如：'Tencent\Model\Bar'）
     * @param string $methodName 类内部的方法名称（可用是实例方法也可以是静态方法）
     * @param array $constructArgs 类构造器参数数组
     * @param array $methodArgs 待调用方法的参数数组
     * @return mixed 调用方法的返回值
     */
    public static function executeMethod($className, $methodName, array $constructArgs = null, array $methodArgs = null)
    {
        $class = self::getReflectionClass($className, $constructArgs);
        $instance = $class->newInstanceArgs((array)$constructArgs);

        $method = $class->getMethod($methodName);
        if (empty($methodArgs)) {
            $result = $method->invoke($instance);
        } else {
            $result = $method->invokeArgs($instance, $methodArgs);
        }
        return $result;
    }

    /**动态调用方法 (相比較ExcuteMethod，這個方法更常用)
     *  这个方法1、即可以一个普通的function，那么第一个直接传入function的名称就可以了
     *         2、也可以是一个对象的method，那么第一个参数要传入一个数组array(实体对象, 方法名称字符串)，例如array($this, "getUser");
     * @param $funcName
     * @param null $funcParam
     * @return mixed
     */
    public static function executeFunction($funcName, $funcParam = null)
    {
        if (is_array($funcParam)) {
            return call_user_func_array($funcName, $funcParam);
        } else {
            return call_user_func($funcName, $funcParam);
        }
    }

    /**
     * @param $className 带命名空间的类型名称
     * @param $funcName 类型的方法名称
     * @param null $funcParam 传递给方法的参数（多个参数之间用^^分隔（主要用于通过url的多个参数的传递））
     * @param bool $returnJson 是否返回JSON格式的数据（缺省为false）
     * @return false|mixed|string
     */
    public static function executeFunctionEx($className ,$funcName, $funcParam = null,  $returnJson = false)
    {
        $object = new $className();
        $targetArray = array($object, $funcName);

        $params = $funcParam;
        if (StringHelper::isContains($funcParam, "^^")) {
            $params = StringHelper::explode($funcParam, "^^");
        }

        if ($funcParam == null) {
            $params = input("funcParam");
        }

        $result = self::executeFunction($targetArray, $params);

        if ($returnJson) {
            return json_encode($result);
        } else {
            return $result;
        }
    }

}