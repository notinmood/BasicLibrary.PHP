<?php
/**
 * @file   : LinkedList.php
 * @time   : 15:19
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataConstruct;

/**
 * 单向链表结构
 * 参考 https://www.cnblogs.com/followyou/p/11162030.html(里面有不少错误,本地已经修正)
 * 关于双向链表等数据结构,请参考spl内信息 https://www.php.net/manual/zh/spl.datastructures.php
 */
class LinkedList
{
    public $head; //头节点(默认一个虚拟头节点,当有第一个节点插入的时候，自动覆盖头节点)
    public $size; //长度

    public function __construct()
    {
        $this->head = new Node(null, null, true);
        $this->size = 0;
    }

    /**
     * 头插法
     * @param $value
     * @return Node|null
     */
    public function addHead($value)
    {
        return $this->insert(0, $value);
    }

    /**
     * 尾插法
     * @param $value
     * @return Node|null
     */
    public function addTail($value)
    {
        return $this->insert($this->size, $value);
    }

    /**
     * 指定索引位置插入
     * @param $index
     * @param $value
     * @return Node|null
     */
    public function insert($index, $value)
    {
        if ($index > $this->size) {
            return null;
        } else {
            $currentNodeOld = $this->head;
            $prevNode = null;
            for ($i = 0; $i < $index; $i++) {
                $prevNode = $currentNodeOld;
                $currentNodeOld = $currentNodeOld->next;
            }

            if ($currentNodeOld->isNullNode) {
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
     * 编辑
     * @param $index
     * @param $value
     * @return Node|mixed|void|null
     */
    public function update($index, $value)
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
     * @param $index
     * @return null
     * @throws Exception
     */
    public function get($index)
    {
        if ($index < $this->size) {
            $current = $this->head;
            for ($i = 0; $i <= $index; $i++) {
                if ($i == $index) {
                    return $current->value;
                }

                $current = $current->next;
            }
        } else {
            return null;
        }
    }

    public function getNode($value)
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
     * @param $index
     */
    public function remove($index)
    {
        if ($index < $this->size) {
            $current = $this->head;
            $prev = null;
            for ($i = 0; $i <= $index; $i++) {
                if ($i == $index) {
                    if ($prev) {
                        $prev->next = $current->next;
                    } else {
                        $this->head = $current->next;
                    }
                }

                $prev = $current;
                $current = $current->next;
            }

            $this->size--;
        }
    }

    /**
     * 检索值是否存在
     * @param $value
     * @return bool
     */
    public function isContains($value)
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
    public function toString()
    {
        $prev = $this->head;

        $r = [];
        while ($prev) {
            $r[] = $prev->value;
            $prev = $prev->next;
        }
        return implode('->', $r);
    }

    // /**
    //  * 删除指定的节点值
    //  * @param $value
    //  */
    // public function removeFields($value)
    // {
    //     $prev = $this->head;
    //     while ($prev->next) {
    //         if ($prev->val == $value) {
    //             $prev->val = $prev->next->val;
    //             $prev->next = $prev->next->next;
    //         } else {
    //             $prev = $prev->next;
    //         }
    //     }
    // }

    // /**
    //  * 通过递归方式删除指定的节点值
    //  * @param $value
    //  * @return mixed
    //  */
    // public function removeFieldsByRecursion($value)
    // {
    //     $this->head = $this->removeByRecursion($this->head, $value);
    //     return $this->head;
    // }
    //
    // public function removeByRecursion($node, $value, $level = 0)
    // {
    //     if ($node->next == null) {
    //         $this->showDeep($level, $node->val);
    //         return $node->val == $value ? $node->next : $node;
    //     }
    //
    //     $this->showDeep($level, $node->val);
    //     $node->next = $this->removeByRecursion($node->next, $value, ++$level);
    //     $this->showDeep($level, $node->val);
    //     return $node->val == $value ? $node->next : $node;
    // }
    //
    // /**
    //  * 显示深度
    //  * 帮助理解递归执行过程，回调函数执行层序遵循系统栈
    //  * @param int $level 深度层级
    //  * @param     $val
    //  * @return bool
    //  */
    // public function showDeep($level = 1, $val)
    // {
    //     if ($level < 1) {
    //         return false;
    //     }
    //
    //     while ($level--) {
    //         echo '-';
    //     }
    //     echo "$val\n";
    // }
}