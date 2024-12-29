<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/6/21
 * Time: 18:20
 */

namespace Hiland\DataModel;


use think\Model;

/**
 * 通用的 Model
 */
class CommonModel extends Model
{
    /**
     * 构造函数
     * @param string $modelName 模型名称
     * @param array $data 数据
     */
    public function __construct(string $modelName, array $data = [])
    {
        $this->name = $modelName;
        parent::__construct($data);
    }
}
