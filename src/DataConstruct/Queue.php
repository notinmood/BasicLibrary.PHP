<?php

namespace Hiland\DataConstruct;

/**
 *
 */
class Queue extends SimpleLinearConstruct
{
    /**
     * （尾部）入队
     * @param mixed $value
     * @return int
     */
    public function push($value): int
    {
        return array_push($this->dataArray, $value);
    }

    /**
     * （头部）出队
     * @return Null|mixed
     */
    public function pop()
    {
        return array_shift($this->dataArray);
    }

    /**
     * 查询当前元素
     * @return NULL|mixed
     */
    public function seek()
    {
        if (empty($this->dataArray)) {
            return null;
        } else {
            $data = $this->dataArray;
            return $data[0];
        }
    }
}
