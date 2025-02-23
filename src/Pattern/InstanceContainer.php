<?php
/**
 * @file   : InstanceContainerTest.php
 * @time   : 20:04
 * @date   : 2021/9/21
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Pattern;

use Hiland\Test\res\Teacher;
use Hiland\Data\ObjectHelper;
use Hiland\Data\StringHelper;
use Psr\Container\ContainerInterface;

/**
 * 单例辅助器(这是一个较简单的实例容器,如果需要更多功能推荐使用Symfony的ContainerBuilder)
 * (缺点：IDE内无法直接实现代码的智能提示)
 * 如果想让IDE进行代码智能提示,可以通过如下方式
 * if ($actualTeacher instanceof Teacher) {
 *      echo $actualTeacher->school;
 * }
 * ════════════════════════
 * 使用方法：
 * $className = Student::class;
 * $actual = InstanceContainerTest::get($className, "张三", 20);
 */
class InstanceContainer
{
    private static array $_instances = [];

    public static function get($classFullName, ...$constructArgs)
    {
        $queryKey = self::builderQueryKey($classFullName, ...$constructArgs);

        if (array_key_exists($queryKey, self:: $_instances)) {
            $instance = self::$_instances[$queryKey];
        } else {
            if ($constructArgs) {
                $instance = new $classFullName(...$constructArgs);
            } else {
                $instance = new $classFullName();
            }

            self:: $_instances[$queryKey] = $instance;
        }

        // ObjectHelper::getTypeName()
        return $instance;
    }

    /**
     * @param string $classFullName 类型全名称
     * @param array  $constructArgs 构造函数的参数
     * @return string
     */
    protected static function builderQueryKey(string $classFullName, ...$constructArgs): string
    {
        $queryKey = $classFullName;
        if ($constructArgs) {
            $queryKey .= "||" . StringHelper::implode($constructArgs, "|");
        }
        return $queryKey;
    }

    /**
     * @param string $classFullName
     * @param array  ...$constructArgs
     * @return bool
     */
    public static function has(string $classFullName, ...$constructArgs): bool
    {
        $queryKey = self::builderQueryKey($classFullName, ...$constructArgs);

        if (array_key_exists($queryKey, self:: $_instances)) {
            return true;
        } else {
            return false;
        }
    }
}
