<?php

namespace Hiland\Utils\DataConstruct;

class Stack extends SimpleLinearConstruct
{
    /**
     * （尾部）出队*
     * @return Null|mixed
     */
    public function pop()
    {
        return array_pop($this->dataArray);
    }

    /**
     * （尾部）入队*
     * @param mixed $value
     * @return int
     */
    public function push($value): int
    {
        return array_push($this->dataArray, $value);
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
            $data   = $this->dataArray;
            $length = count($data);
            return $data[$length - 1];
        }
    }
}