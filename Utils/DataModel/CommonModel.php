<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/6/21
 * Time: 18:20
 */

namespace Hiland\Utils\DataModel;


use Hiland\Biz\ThinkAddon\TPCompatibleHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\ReflectionHelper;
use Hiland\Utils\Data\ThinkHelper;
use ReflectionException;
use think\Config;
use think\Model;

/**
 * 通用的Model
 * ════════════════════════
 * 继承的Model中的initialize方法,请修改为protected(否则会启用反射,影响性能)
 */
class CommonModel extends Model
{
    /**
     * @throws ReflectionException
     */
    public function __construct($modelName, $data = [])
    {
        if (is_object($data)) {
            $this->data = get_object_vars($data);
        } else {
            $this->data = $data;
        }
        // 记录原始数据
        $this->origin = $this->data;

        // 当前类名
        $this->class = "Hiland\\Utils\\DataModel\\" . $modelName;

        if (empty($this->name)) {
            // 当前模型名
            $name = str_replace('\\', '/', $this->class);
            $this->name = basename($name);
        }

        if (!empty(static::$maker)) {
            foreach (static::$maker as $maker) {
                call_user_func($maker, $this);
            }
        }

        $methodName = "initialize";
        if (is_callable($this, $methodName)) {
            $this:: initialize();
        } else {
            $className = ObjectHelper::getClassName($this);
            ReflectionHelper::executeInstanceMethod($className, $methodName, $this);
        }
    }
}