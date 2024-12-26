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
     *
     */
    public function __construct($modelName, $data = [])
    {
        $this->name = $modelName;
        parent::__construct($data);
    }
}
