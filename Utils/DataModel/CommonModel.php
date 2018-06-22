<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/6/21
 * Time: 18:20
 */

namespace Hiland\Utils\DataModel;


use think\Config;
use think\Model;

class CommonModel extends Model
{
    public function __construct($modelName,$data = [])
    {
        if (is_object($data)) {
            $this->data = get_object_vars($data);
        } else {
            $this->data = $data;
        }
        // 记录原始数据
        $this->origin = $this->data;

        // 当前类名
        $this->class = "Hiland\\Utils\\DataModel\\".$modelName;//get_called_class();

        if (empty($this->name)) {
            // 当前模型名
            $name       = str_replace('\\', '/', $this->class);
            $this->name = basename($name);
            if (Config::get('class_suffix')) {
                $suffix     = basename(dirname($name));
                $this->name = substr($this->name, 0, -strlen($suffix));
            }
        }

        if (is_null($this->autoWriteTimestamp)) {
            // 自动写入时间戳
            $this->autoWriteTimestamp = $this->getQuery()->getConfig('auto_timestamp');
        }

        if (is_null($this->dateFormat)) {
            // 设置时间戳格式
            $this->dateFormat = $this->getQuery()->getConfig('datetime_format');
        }

        if (is_null($this->resultSetType)) {
            $this->resultSetType = $this->getQuery()->getConfig('resultset_type');
        }
        // 执行初始化操作
        $this->initialize();
    }
}