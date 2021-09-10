<?php

namespace Hiland\Utils\DataConstruct;

use Exception;

/**
 * 简单的线性数据结构
 */
abstract class SimpleLinearConstruct
{

    protected $dataArray = array();

    public function __construct($data = null)
    {
        if (!empty($data)) {
            $this->dataArray = $data;
        }
    }

    /**
     * 入队 *
     * @param $value
     * @throws Exception
     */
    public function push($value)
    {
        $myValue = $value;
        throw new Exception('请在派生类实现入队操作');
    }

    /**
     * 出队*
     * @throws Exception
     */
    public function pop()
    {
        throw new Exception('请在派生类实现出队操作');
    }


    /**
     * 查询当前元素
     * @return mixed|NULL
     * @throws Exception
     */
    public function seek()
    {
        throw new Exception('请在派生类实现查询当前元素操作');
    }

    /**
     * 清空队列*
     */
    public
    function makeEmpty()
    {
        unset($this->dataArray);
    }

    /**
     * 获取长度 *
     */
    public function getLength()
    {
        return count($this->dataArray);
    }
}