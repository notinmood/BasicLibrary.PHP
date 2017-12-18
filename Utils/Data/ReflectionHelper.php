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
}