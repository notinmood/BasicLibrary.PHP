<?php
/**
 * @file   : LinkedList.php
 * @time   : 15:19
 * @date   : 2021/9/6
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\DataConstruct;

/**
 * 单向链表结构
 * 参考 https://www.cnblogs.com/followyou/p/11162030.html(里面有不少错误,本地已经修正)
 * 关于双向链表等数据结构,请参考 spl 内信息 https://www.php.net/manual/zh/spl.datastructures.php
 */
class LinkedList
{
    /**
     * 头节点(默认一个虚拟头节点,当有第一个节点插入的时候，自动覆盖头节点)
     * @var Node
     */
    public Node $head;

    /**
     * 长度
     * @var int
     */
    public int $size;

    public function __construct()
    {
        $this->head = new Node(null, null, true);
        $this->size = 0;
    }

    /**
     * 头插法
     * @param mixed $value
     * @return Node|null
     */
    public function addHead($value): ?Node
    {
        return $this->insert(0, $value);
    }

    /**
     * 尾插法
     * @param mixed $value
     * @return Node|null
     */
    public function addTail($value): ?Node
    {
        return $this->insert($this->size, $value);
    }

    /**
     * 指定索引位置插入
     * @param int   $index
     * @param mixed $value
     * @return Node|null
     */
    public function insert(int $index, $value): ?Node
    {
        if ($index > $this->size) {
            return null;
        } else {
            $currentNodeOld = $this->head;
            $prevNode       = null;
            for ($i = 0; $i < $index; $i++) {
                $prevNode       = $currentNodeOld;
                $currentNodeOld = $currentNodeOld->next;
            }

            if (!$currentNodeOld || $currentNodeOld->isNullNode) {
                $currentNodeNew = new Node($value, null);
            } else {
                $currentNodeNew = new Node($value, $currentNodeOld);
            }

            if ($prevNode) {
                $prevNode->next = $currentNodeNew;
            } else {
                $this->head = $currentNodeNew;
            }

            $this->size++;
            return $currentNodeNew;
        }
    }


    /***
     * 更新
     * @param int   $index
     * @param mixed $value
     * @return Node|mixed|void|null
     */
    public function update(int $index, $value)
    {
        if ($index >= $this->size) {
            return null;
        } else {
            $current = $this->head;
            for ($i = 0; $i <= $index; $i++) {
                if ($i == $index) {
                    $current->value = $value;
                    return $current;
                }

                $current = $current->next;
            }
        }
    }

    /**
     * 查询
     * @param int $index
     * @return mixed
     */
    public function getValue(int $index)
    {
        if ($index < $this->size) {
            $current = $this->head;
            for ($i = 0; $i <= $index; $i++) {
                if ($i == $index) {
                    return $current->value;
                }

                $current = $current->next;
            }
        }

        return null;
    }

    /**
     * 通过 Value 获取节点
     * @param mixed $value
     * @return Node|mixed|null
     */
    public function getNodeWithValue($value)
    {
        $current = $this->head;
        while ($current) {
            if ($current->value == $value) {
                return $current;
            }

            $current = $current->next;
        }

        return null;
    }


    /**
     * 删除
     * @param int $index
     */
    public function remove(int $index)
    {
        if ($index < $this->size) {
            $current = $this->head;
            $prev    = null;
            for ($i = 0; $i <= $index; $i++) {
                if ($i == $index) {
                    if ($prev) {
                        $prev->next = $current->next;
                    } else {
                        $this->head = $current->next;
                    }
                }

                $prev    = $current;
                $current = $current->next;
            }

            $this->size--;
        }
    }

    /**
     * 检索值是否存在
     * @param mixed $value
     * @return bool
     */
    public function isContains($value): bool
    {
        $current = $this->head;
        while ($current) {
            if ($current->value == $value) {
                return true;
            }

            $current = $current->next;
        }

        return false;
    }

    /**
     * 转换为字符串
     * @return string
     */
    public function toString(): string
    {
        $prev = $this->head;

        $r = [];
        while ($prev) {
            $r[]  = $prev->value;
            $prev = $prev->next;
        }
        return implode('->', $r);
    }
}
