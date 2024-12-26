<?php

namespace Hiland\Data;

use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

/**
 * Abstract class that enables creation of PHP enums.
 * All you
 * have to do is extend this class and define some constants.
 * Enum is an object with value from on of those constants
 * (or from on of superclass if any). There is also
 * __default constat that enables you creation of object
 * without passing enum value.
 * @author Marijan Šuflaj <msufflaj32@gmail.com&gt
 * @link   http://www.php4every1.com/scripts/php-enum/
 */

/**
 * 使用方法:
 * 1、创建派生于Enum的自定义类型,在类型内部通过const定义一系列"枚举成员"
 * class MyEnum extends Enum
 * {
 * const HI = "hi";
 * const BYE = "good bye";
 * const __default = self::HI;
 * }
 * 2、给自定义类型的构造方法传入相应的 枚举成员表示的值,得到一个自定义枚举类型实例
 * (也就是说通过构造方法实现了 枚举值和枚举成员的 转换)
 * var_dump(new MyEnum()); //使用 __default的枚举成员
 * var_dump(new MyEnum(MyEnum::BYE));
 * var_dump(new MyEnum("hi")); //直接传统枚举值,将转换为目标的枚举成员
 */
abstract class Enum
{
    /**
     * Constant with default value for creating enum object
     */
    const __default = null;
    private static array $constants = array();
    private $value;
    private bool $strict;

    /**
     * Creates new enum object.
     * If child class overrides __construct(),
     * it is required to call parent::__construct() in order for this
     * class to work as expected.
     * @param mixed $initialValue
     *            Any value that is exists in defined constants
     * @param bool $strict
     *            If set to true, type and value must be equal
     * @throws UnexpectedValueException If value is not valid enum value
     */
    public function __construct($initialValue = null, bool $strict = true)
    {
        $class = get_class($this);

        if (!array_key_exists($class, self::$constants)) {
            self::populateConstants();
        }

        if ($initialValue === null) {
            $initialValue = self::$constants[$class]["__default"];
        }

        $temp = self::$constants[$class];

        if (!in_array($initialValue, $temp, $strict)) {
            throw new UnexpectedValueException("Value is not in enum " . $class);
        }

        $this->value  = $initialValue;
        $this->strict = $strict;
    }

    /**
     * Returns list of all defined constants in enum class.
     * Constants value are enum values.
     * @param bool $includeDefault
     *            If true, default value is included into return
     * @return array Array with constant values
     */
    public function getConstList(bool $includeDefault = false): array
    {
        $class = get_class($this);

        if (!array_key_exists($class, self::$constants)) {
            self::populateConstants();
        }

        return $includeDefault ? array_merge(self::$constants[__CLASS__], array(
            "__default" => self::__default,
        )) : self::$constants[__CLASS__];
    }

    private function populateConstants()
    {
        $class = get_class($this);

        $r = null;
        try {
            $r = new ReflectionClass($class);
        } catch (ReflectionException $e) {
        }

        $constants = $r->getConstants();

        self::$constants = array(
            $class => $constants,
        );
    }

    /**
     * Returns string representation of an enum.
     * Defaults to
     * value cast to string.
     * @return string String representation of this enum's value
     */
    public function toString(): string
    {
        return (string)$this->value;
    }

    /**
     * Checks if two enums are equal.
     * Only value is checked, not class type also.
     * If enum was created with $strict = true, then strict comparison applies
     * here also.
     * @param mixed $object
     * @return bool True if enums are equal.
     */
    public function equals($object): bool
    {
        if (!($object instanceof Enum)) {
            return false;
        }
        return $this->strict ? ($this->value === $object->value) : ($this->value == $object->value);
    }
}
