<?php
namespace Hiland\Utils\DataConstructure;

abstract class BasicQueueStack
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
     * @throws \Exception
     */
    public function push($value)
    {
        $myValue = $value;
        throw new \Exception('请在派生类实现入队操作');
    }

    /**
     * 出队*
     */
    public function pop()
    {
        throw new \Exception('请在派生类实现出队操作');
    }


    /**
     * 查询当前元素
     * @return mixed|NULL
     * @throws \Exception
     */
    public function seek()
    {
        throw new \Exception('请在派生类实现查询当前元素操作');
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

?>