<?php

namespace Hiland\Utils\Data;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ReflectionHelper
{
    /**
     * 通过反射创建对象实例
     * @param string $className 类名称字符串（如果有命名空间的话带命名空间，例如 Tencent\Model\Foo2）
     * @param array  $args      类构造器参数数组(类似["zhangsan", 20]这样的一维索引数组)
     * @return object
     * @throws ReflectionException
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
     * @param string $className 类名称（如果有命名空间，请携带命名空间，如：Tencent\Model\Bar）
     * @param array  $args      类构造器参数数组 (类似["zhangsan", 20]这样的一维索引数组)
     * @return ReflectionClass
     */
    public static function getReflectionClass($className, &$args = null)
    {
        $refClass = null;
        try {
            $refClass = new ReflectionClass($className);
        } catch (ReflectionException $e) {
        }

        if (isset($args) && !isset($args[0])) {
            $args2 = array();
            try {
                foreach ($refClass->getMethod('__construct')->getParameters() as $param) {
                    if (isset($args[$param->name])) {
                        $args2[] = $args[$param->name];
                    } elseif ($param->isDefaultValueAvailable()) {
                        $args2[] = $param->getDefaultValue();
                    }
                }
            } catch (ReflectionException $e) {
            }
            $args = $args2;
        }

        return $refClass;
    }

    /**
     * 执行某个类里面的实例方法
     * (参数$instance 和 $constructArgs 设置其一即可)
     * @param string $className     类名称（如果有命名空间，请携带命名空间，如：Tencent\Model\Bar）
     * @param string $methodName    类内部的方法名称（可用是实例方法也可以是静态方法）
     * @param null   $instance      具体的类型实例
     * @param array  $constructArgs 类构造器参数数组(类似["zhangsan", 20]这样的一维索引数组)
     *                              (如果没有指定$instance,那么便可以通过本参数进行实例化一个对象;如果已经指定了$instance,本参数将忽略.)
     * @param array  $methodArgs    待调用方法的参数数组
     * @return mixed 调用方法的返回值
     * @throws ReflectionException
     */
    public static function executeInstanceMethod($className, $methodName, $instance = null, array $constructArgs = null, array $methodArgs = null)
    {
        $result = null;
        $class = self::getReflectionClass($className, $constructArgs);
        $method = $class->getMethod($methodName);

        if ($method) {
            //如果是私有方法,通过此处访问设置可见性,依然可以从外表访问
            $method->setAccessible(TRUE);

            if (!$instance) {
                $instance = $class->newInstanceArgs((array)$constructArgs);
            }

            if (empty($methodArgs)) {
                $result = $method->invoke($instance);
            } else {
                $result = $method->invokeArgs($instance, $methodArgs);
            }
        }

        return $result;
    }

    /**
     * 通过属性名称获取属性值(通常用于获取私有属性的场景)
     * (参数$instance 和 $constructArgs 设置其一即可)
     * @param string     $className     类型名称字符串(通常使用 <Class>::class这样的形式简便获得)
     * @param string     $propertyName  待获取属性值的属性名称
     * @param null       $instance      对象实例
     * @param array|null $constructArgs 对象构造方法中使用的参数(类似["zhangsan", 20]这样的一维索引数组)
     *                                  (如果没有指定$instance,那么便可以通过本参数进行实例化一个对象;如果已经指定了$instance,本参数将忽略.)
     * @return mixed|null
     * @throws ReflectionException
     */
    public static function getInstanceProperty($className, $propertyName, $instance = null, array $constructArgs = null)
    {
        $class = self::getReflectionClass($className, $constructArgs);
        $property = $class->getProperty($propertyName);

        if ($property) {
            // 设置目标的可访问性
            $property->setAccessible(true);

            if (!$instance) {
                $instance = $class->newInstanceArgs((array)$constructArgs);
            }
            return $property->getValue($instance);
        } else {
            return null;
        }
    }

    /**
     * 执行某个类里面的静态方法
     * @param $className
     * @param $methodName
     * @param ...$methodArgs
     * @return mixed|null
     */
    public static function executeStaticMethod($className, $methodName, ...$methodArgs)
    {
        $result = null;
        $method = null;
        try {
            $method = new ReflectionMethod($className, $methodName);
        } catch (ReflectionException $e) {
        }

        if ($method) {
            //如果是私有方法,通过此处访问设置可见性,依然可以从外表访问
            $method->setAccessible(TRUE);
            try {
                $result = $method->invoke(null, ...$methodArgs);
            } catch (ReflectionException $e) {
            }
        }

        return $result;
    }

    /**
     * @param string $className  带命名空间的类型名称
     * @param string $funcName   类型的方法名称
     * @param null   $funcParam  传递给方法的参数（多个参数之间用^^分隔（主要用于通过url的多个参数的传递））
     * @param bool   $returnJson 是否返回JSON格式的数据（缺省为false）
     * @return false|mixed|string
     */
    public static function executeFunctionEx($className, $funcName, $funcParam = null, $returnJson = false)
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

    /**动态调用方法 (相比較ExecuteMethod，這個方法更常用)
     *  这个方法1、即可以一个普通的function，那么第一个直接传入function的名称就可以了
     *         2、也可以是一个对象的method，那么第一个参数要传入一个数组array(实体对象, 方法名称字符串)，例如array($this, "getUser");
     * @param      $funcName
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
}