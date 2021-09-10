<?php
/**
 * @file   : Node.php
 * @time   : 15:35
 * @date   : 2021/9/6
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataConstruct;

/**
 * 链表LinkedList的节点类型
 */
class Node
{
    /**
     * 表示当前是一个没有任何意义的空节点
     * @var false|mixed
     */
    public $isNullNode;

    public $value;
    public $next;

    public function __construct($value = null, $next = null, $isNullNode = false)
    {
        $this->value = $value;
        $this->next = $next;
        $this->isNullNode = $isNullNode;
    }
}