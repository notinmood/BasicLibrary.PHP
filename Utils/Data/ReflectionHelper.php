<?php

namespace Hiland\Utils\Data;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * 反射帮助器
 * (除私有方法外，对类型(或者实例)的各种方法的执行，建议统一使用 executeFunction)
 */
class ReflectionHelper
{
    /**
     * 通过反射创建对象实例
     * @param string     $className 类名称字符串（如果有命名空间的话带命名空间，例如 Tencent\Model\Foo2）
     * @param array|null $args      类构造器参数数组(类似["zhangsan", 20]这样的一维索引数组)
     * @return object
     * @throws ReflectionException
     */
    public static function createInstance(string $className, array $args = null): object
    {
        if (empty($args)) {
            return new $className();
        } else {
            $class = self::getReflectionClass($className, $args);
            return $class->newInstanceArgs($args);
        }
    }

    /**
     * 获取类型的反射信息
     * @param string     $className 类名称（如果有命名空间，请携带命名空间，如：Tencent\Model\Bar）
     * @param array|null $args      类构造器参数数组 (类似["zhangsan", 20]这样的一维索引数组)
     * @return ReflectionClass
     */
    public static function getReflectionClass(string $className, array &$args = null): ?ReflectionClass
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
     * 执行某个类里面的实例方法(可以是 private 级别的方法)
     * (参数$instance 和 $constructArgs 设置其一即可)
     * @param string     $className     类名称（如果有命名空间，请携带命名空间，如：Tencent\Model\Bar）
     * @param string     $methodName    类内部的方法名称（可用是实例方法也可以是静态方法）
     * @param null       $instance      具体的类型实例
     * @param array|null $constructArgs 类构造器参数数组(类似["zhangsan", 20]这样的一维索引数组)
     *                                  (如果没有指定$instance,那么便可以通过本参数进行实例化一个对象;如果已经指定了$instance,本参数将忽略.)
     * @param array|null $methodArgs    待调用方法的参数数组
     * @return mixed 调用方法的返回值
     */
    public static function executeInstanceMethod(string $className, string $methodName, $instance = null, array $constructArgs = null, array $methodArgs = null)
    {
        $result = null;
        $class  = self::getReflectionClass($className, $constructArgs);
        try {
            $method = $class->getMethod($methodName);
        } catch (ReflectionException $e) {
        }

        if ($method) {
            //如果是私有方法,通过此处访问设置可见性,依然可以从外表访问
            $method->setAccessible(TRUE);

            if (!$instance) {
                try {
                    $instance = $class->newInstanceArgs((array)$constructArgs);
                } catch (ReflectionException $e) {
                }
            }

            if (empty($methodArgs)) {
                try {
                    $result = $method->invoke($instance);
                } catch (ReflectionException $e) {
                }
            } else {
                try {
                    $result = $method->invokeArgs($instance, $methodArgs);
                } catch (ReflectionException $e) {
                }
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
     */
    public static function getInstanceProperty(string $className, string $propertyName, $instance = null, array $constructArgs = null)
    {
        $class = self::getReflectionClass($className, $constructArgs);
        try {
            $property = $class->getProperty($propertyName);
        } catch (ReflectionException $e) {
        }

        if ($property) {
            // 设置目标的可访问性
            $property->setAccessible(true);

            if (!$instance) {
                try {
                    $instance = $class->newInstanceArgs((array)$constructArgs);
                } catch (ReflectionException $e) {
                }
            }
            return $property->getValue($instance);
        } else {
            return null;
        }
    }

    /**
     * 执行某个类里面的静态方法(可以是 private 级别的方法)
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
     * 动态调用方法 (相比較 ExecuteMethod，這個方法更常用)
     *  这个方法 1、即可以一个普通的 function，那么第一个直接传入 function 的名称就可以了
     *          2、也可以是一个对象的实例 method，那么第一个参数要传入一个数组 array(实体对象, 方法名称字符串)，例如 [$this, "getUser"];
     *          3、也可以是一个对象的静态 method，那么第一个参数要传入一个数组 array(类型的全名称, 方法名称字符串)，例如 [Student::class, "getUser"];
     * @param callable $funcFullName  函数的全名称(包含命名空间，类型信息的函数或方法名称)
     * @param mixed    ...$funcParams 函数或者方法的参数信息
     * @return mixed
     */
    public static function executeFunction(callable $funcFullName, ...$funcParams)
    {
        return call_user_func($funcFullName, ...$funcParams);
    }
}