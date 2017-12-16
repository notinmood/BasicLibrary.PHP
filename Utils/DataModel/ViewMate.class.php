<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/7
 * Time: 18:38
 */

namespace Hiland\Utils\DataModel;


use Think\Model\RelationModel;

/**
 * Class ViewMate
 * @package Vendor\Hiland\Utils\DataModel
 *
 * ==非常重要veryImportant================
 * 此类型要求 RelationModel添加如下方法
 * public function setLink($link){
 * $this->_link= $link;
 * }
 */
class ViewMate extends ModelMate
{
    const   HAS_ONE = 1;
    const   BELONGS_TO = 2;
    const   HAS_MANY = 3;
    const   MANY_TO_MANY = 4;

    //var $model;

    /**
     * 构造函数
     *
     * @param string|model $model
     *            其可以是一个表示model名称的字符串；
     *            也可以是一个继承至Think\Model的类型
     * @param array $link 关联信息
     */
    public function __construct($model, $link = array())
    {
//        if (empty($model)) {
//            $ns = __NAMESPACE__;
//            $cn = __CLASS__;
//
//            if ($ns) {
//                $cn = StringHelper::getSeperatorAfterString($cn, $ns);
//            }
//            $cn = StringHelper::getSeperatorBeforeString($cn, 'ViewMate');
//            $cn = StringHelper::getSeperatorAfterString($cn, "\\");
//
//            $model= $cn;
//        }

        if (is_string($model)) {
            $this->model = new RelationModel($model);
        } else {
            $this->model = $model;
        }

        $this->model->setLink($link);
    }

    /**
     * 按照主键获取信息
     *
     * @param int|string $key
     *            查询信息的主键值
     * @param string $keyName
     *            查询信息的主键名称
     * @param bool $useRelation 是否启用关联数据
     * @return array 模型实体数据
     */
    public function get($key, $keyName = 'id', $useRelation = true)
    {
        $model = $this->getModel_Get($key, $keyName);

        if ($useRelation) {
            $model = $model->relation($useRelation);
        }

        $data = $model->find();
        return $data;
    }

    /**
     * 根据条件获取一条记录
     * @param array $condition 过滤条件
     * @param bool $useRelation 是否启用关联数据
     * @return array 符合条件的结果，一维数组
     * @example
     * $where= array();
     * $where['shopid'] = $merchantScanedID;
     * $where['openid'] = $openId;
     * $result = $buyerShopMate->find($where);
     */
    public function find($condition = array(), $useRelation = true)
    {
        $model = $this->getModel_Where($condition);

        if ($useRelation) {
            $model = $model->relation($useRelation);
        }

        return $model->find();
    }

    /**
     * 根据条件获取多条记录
     * @param array $condition
     * @param bool $useRelation 是否启用关联数据
     * @param string $orderBy 排序信息
     * @param int $pageIndex 页面序号
     * @param int $itemCountPerPage 每页显示的信息条目数
     * @param int $limit 查询信息的条目数
     * @return array 符合条件的结果，多维数组
     * @example
     * $where= array();
     * $where['shopid'] = $merchantScanedID;
     * $where['openid'] = $openId;
     * $relation = $buyerShopMate->select($where);
     */
    public function select($condition = array(), $useRelation = true, $orderBy = "", $pageIndex = 0, $itemCountPerPage = 0, $limit = 0)
    {
        if(empty($useRelation)){
            $useRelation= false;
        }
        if(empty($orderBy)){
            $orderBy = "id desc";
        }

        $model = $this->getModel_Select($condition, $orderBy, $pageIndex, $itemCountPerPage, $limit);

        if ($useRelation) {
            $model = $model->relation($useRelation);
        }

        return $model->select();
    }

    protected function getClassName()
    {
        return __CLASS__;
    }
}