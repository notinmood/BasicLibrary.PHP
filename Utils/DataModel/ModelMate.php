<?php

namespace Hiland\Utils\DataModel;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\ObjectTypes;
use Hiland\Utils\DataValue\SystemEnum;
use think\db\BaseQuery;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\db\Query;
use think\facade\Db;
use Think\Model;

/**
 * 模型辅助器(需要ThinkPHP或者ThinkORM支持)
 * 封装模型与数据库交互的常用操作
 * ════════════════════════
 * 使用说明：
 * 1.选取数据(或数据集)时过滤条件的设定，请参考本级目录下的 _README.md 文件
 */
class ModelMate
{
    /**
     * @var CommonModel|Model
     */
    var $modelObject;
    /**
     * @var BaseQuery|Db
     */
    var $queryObject;
    /**
     * @var string
     */
    private $tableRealName;

    /**
     * 构造函数
     * @param string|model $model
     *            其可以是一个表示model名称的字符串；
     *            也可以是一个继承至Think\Model的类型
     * @TODO Think\Model的类型未严格验证
     */
    public function __construct($model)
    {
        if (is_string($model)) {
            $this->modelObject = new CommonModel($model);

            $className = "\\think\\facade\\Db";
            $exist     = class_exists("$className");
            if ($exist) {
                /** @noinspection all */
                $this->queryObject = Db::name($model);
            } else {
                $className = "\\think\\Db";
                $exist     = class_exists("$className");
                if ($exist) {
                    /** @noinspection all */
                    $this->queryObject = \think\Db::name($model);
                }
            }
        } else {
            $this->modelObject = $model;
            $this->queryObject = $model->db();
        }

        $this->tableRealName = $this->modelObject->getTable();
    }

    /**
     * 获取数据库真实的表名称(包含了表前缀)
     * @return string
     */
    public function getTableRealName(): string
    {
        return $this->tableRealName;
    }

    //------基础的CRUD操作------------------------------------------------

    /**
     * 按照主键获取信息
     * @param int|string $key     查询信息的主键值
     * @param string     $keyName 查询信息的主键名称
     * @return array|null 模型实体数据
     */
    public function get($key, string $keyName = 'id'): ?array
    {
        try {
            return self::getQueryObjectWithGet($key, $keyName)->find();
        } catch (DataNotFoundException|ModelNotFoundException|DbException $e) {
            return null;
        }
    }

    /**
     * 根据条件获取一条记录
     * @param array  $condition 过滤条件
     * @param string $orderBy
     * @return mixed 符合条件的结果，一维数组
     * @example
     *                          $where= array();
     *                          $where['shopID'] = $merchantScannedID;
     *                          $where['openID'] = $openId;
     *                          $result = $buyerShopMate->find($where);
     */
    public function find(array $condition = array(), string $orderBy = '')
    {
        $query = $this->getQueryObjectWithWhere($condition);
        try {
            return $query->order($orderBy)->find();
        } catch (DataNotFoundException|ModelNotFoundException|DbException $e) {
            return null;
        }
    }

    /**
     * 根据条件获取多条记录
     * @param array  $condition
     * @param string $orderBy          排序信息
     * @param int    $pageIndex        页面序号
     * @param int    $itemCountPerPage 每页显示的信息条目数
     * @param int    $limit            查询信息的条目数
     * @param string $fields           需要在查询结果中显示的字段信息，缺省情况下显示全部字段
     * @return mixed 符合条件的结果，多维数组
     * @example
     *                                 $where= array();
     *                                 $where['shopID'] = $merchantScannedID;
     *                                 $where['openID'] = $openId;
     *                                 $relation = $buyerShopMate->select($where);
     */
    public function select(array $condition = array(), string $orderBy = "", int $pageIndex = 0, int $itemCountPerPage = 0, int $limit = 0, string $fields = '')
    {
        if (empty($orderBy)) {
            $orderBy = "id desc";
        }

        $query = $this->getQueryObjectWithSelect($condition, $orderBy, $pageIndex, $itemCountPerPage, $limit);

        if ($fields) {
            try {
                return $query->field($fields)->select();
            } catch (DataNotFoundException|ModelNotFoundException|DbException $e) {
                return null;
            }
        } else {
            try {
                return $query->select();
            } catch (DataNotFoundException|ModelNotFoundException|DbException $e) {
                return null;
            }
        }
    }

    /**
     * 获取满足条件的记录数
     * @param array $condition
     * @return int
     */
    public function getCount(array $condition = array()): int
    {
        $query = $this->getQueryObjectWithWhere($condition);
        return $query->count();
    }

    /**
     * 跟数据库的信息交互(insert 或者 update)
     * @param array|null $data    待跟数据库交互的模型实体数据
     * @param string     $keyName 当前模型的数据库表的主键名称
     * @return boolean|number
     */
    public function interact(array $data = null, string $keyName = 'id')
    {
        if (empty($data)) {
            /* 获取数据对象 */
            $data = $this->modelObject->create($_POST);
        }

        if (empty($data)) {
            return false;
        }

        $isAddOperation = true;

        /* 添加或新增基础内容 */
        if (empty($data[$keyName])) { // 新增数据
            $recordID = $this->queryObject->insert($data, false);
            $recordID = (int)$recordID;

            if (!$recordID) {
                //$this->model->setError('新增数据出错！');
                return false;
            }
        } else { // 更新数据
            $recordID       = $data[$keyName];
            $isAddOperation = false;

            $status = false;
            try {
                $status = $this->queryObject->update($data);
            } catch (DbException $e) {
            }

            if (false === $status) {
                //$this->model->setError('更新数据出错！');
                return false;
            }
        }

        // TODO:需要并研究添加hook机制
        // hook('documentSaveComplete', array('model_id'=>$data['model_id']));

        // 行为记录
        if ($recordID && $isAddOperation) {
            // action_log('add_role', 'role', $recordID, UID);
        }

        // 内容添加或更新完成
        return $recordID;
    }

    /**
     * 删除数据
     * @param array $condition
     * @return int 失败返回false；成功返回删除数据的条数
     */
    public function delete(array $condition = array()): int
    {
        $query = $this->getQueryObjectWithWhere($condition);
        try {
            return $query->delete();
        } catch (DbException $e) {
            return false;
        }
    }

    /**
     * 获取某记录的字段的值
     * @param int|string $key
     * @param string     $fieldName
     * @param string     $keyName
     * @return mixed 字段的值
     */
    public function getValue($key, string $fieldName, string $keyName = 'id')
    {
        $condition[$keyName] = $key;
        $query               = $this->getQueryObjectWithWhere($condition);
        try {
            $result = $query->find();
            return $result[$fieldName];
        } catch (DataNotFoundException|ModelNotFoundException|DbException $e) {
            return null;
        }
    }

    /**
     * 设置某记录的字段的值
     * @param int|string $key
     * @param string     $fieldName
     * @param mixed      $fieldValue
     * @param string     $keyName
     * @return int|bool 成功时返回受影响的行数，失败时返回false
     */
    public function setValue($key, string $fieldName, $fieldValue, string $keyName = 'id'): int
    {
        $condition[$keyName] = $key;
        $query               = $this->getQueryObjectWithWhere($condition);
        $data["$fieldName"]  = $fieldValue;
        try {
            return $query->update($data);
        } catch (DbException $e) {
            return false;
        }
    }



    //------直接跟数据库交互的操作------------------------------------------------

    /**
     * 执行SQL语句，如果语句里面涉及到本模型对应的表名称，建议不要直接写。可以使用“关键字”  __MODELTABLENAME__,或者__MTN__,推荐使用 __TABLE__ ，本函数自动翻译为带前缀的表名称
     * @param string $sql
     * @return mixed
     */
    public function directlyQuery(string $sql)
    {
        $tableName = $this->queryObject->getTable();

        if (strstr($sql, '__MODELTABLENAME__')) {
            $sql = str_replace('__MODELTABLENAME__', $tableName, $sql);
        }

        if (strstr($sql, '__MTN__')) {
            $sql = str_replace('__MTN__', $tableName, $sql);
        }

        return $this->queryObject->getConnection()->query($sql);
    }

    /**
     * 执行原始的sql语句
     * @param string $sql
     * @return false|int
     */
    public function directlyExecute(string $sql)
    {
        return $this->queryObject->getConnection()->execute($sql);
    }


    //------自增自减的数据操作------------------------------------------------

    // /**
    //  * @param     $condition
    //  * @param     $field
    //  * @param int $step
    //  * @param int $lazyTime
    //  * @return bool
    //  */
    // public function setInc($condition, $field, $step = 1, $lazyTime = 0)
    // {
    //     return $this->modelObject->where($condition)->inc($field, $step, $lazyTime);
    // }
    //
    // /**
    //  * @param     $condition
    //  * @param     $field
    //  * @param int $step
    //  * @param int $lazyTime
    //  * @return bool
    //  */
    // public function setDec($condition, $field, $step = 1, $lazyTime = 0)
    // {
    //     return $this->modelObject->where($condition)->setDec($field, $step, $lazyTime);
    // }

    //------获取带条件的查询对象------------------------------------------------

    /**
     * 获取get数据时候需要的 Query
     * @param mixed  $key
     * @param string $keyName
     * @return Query
     */
    protected function getQueryObjectWithGet($key, string $keyName = 'id')
    {
        $condition[$keyName] = $key;
        return self::getQueryObjectWithWhere($condition);
    }

    /**
     * 根据条件获取Select需要的model
     * @param array  $condition
     * @param string $orderBy          排序信息
     * @param int    $pageIndex        页面序号
     * @param int    $itemCountPerPage 每页显示的信息条目数
     * @param int    $limit            查询信息的条目数
     * @return Model
     */
    protected function getQueryObjectWithSelect(array $condition = array(), string $orderBy = "id desc", int $pageIndex = 0, int $itemCountPerPage = 0, int $limit = 0)
    {
        $query = $this->getQueryObjectWithWhere($condition);

        if ($pageIndex && $itemCountPerPage) {
            $query = $query->page($pageIndex, $itemCountPerPage);
        }

        if ($limit) {
            $query = $query->limit($limit);
        }

        if ($orderBy) {
            $query = $query->order($orderBy);
        }

        return $query;
    }

    private bool $queried = false;

    /**
     * 获取加入where过滤条件的 Query
     * @param array $condition
     * @return Query
     */
    protected function getQueryObjectWithWhere(array $condition = array())
    {
        if ($this->queried) {
            $this->queryObject = $this->queryObject->newQuery();
        }
        $this->queried = true;

        $this->_parseWhereCondition($condition);
        return $this->queryObject;
    }

    /**
     * @param array $conditions
     * @return void
     */
    private function _parseWhereCondition(array $conditions = [])
    {
        foreach ($conditions as $key => $value) {
            switch ($key) {
                case SystemEnum::WhereConnector_OR:
                    $this->_parseWhereOrCondition($value);
                    break;
                case SystemEnum::WhereConnector_AND:
                    $this->_parseWhereAndCondition($value);
                    break;
                default:
                    $this->_parseWhereAndConditionDetail($key, $value);
            }
        }
    }

    private function _parseWhereOrCondition($conditions)
    {
        foreach ($conditions as $key => $value) {
            $this->_parseWhereOrConditionDetail($key, $value);
        }
    }

    private function _parseWhereOrConditionDetail($key, $value)
    {
        $queryObject = $this->queryObject;
        if (ObjectHelper::getTypeName($value) == ObjectTypes::ARRAYS) {
            if (ArrayHelper::getLevel($value) == 1) {
                $queryObject->whereOr($key, array_keys($value)[0], array_values($value)[0]);
            } else {
                foreach ($value as $secondKey => $secondValue) {
                    $queryObject->whereOr($key, $secondKey, $secondValue);
                }
            }
        } else {
            $queryObject->whereOr($key, $value);
        }
    }

    private function _parseWhereAndCondition($conditions)
    {
        foreach ($conditions as $key => $value) {
            $this->_parseWhereAndConditionDetail($key, $value);
        }
    }

    private function _parseWhereAndConditionDetail($key, $value)
    {
        $queryObject = $this->queryObject;
        if (ObjectHelper::getTypeName($value) == ObjectTypes::ARRAYS) {
            if (ArrayHelper::getLevel($value) == 1) {
                $queryObject->where($key, array_keys($value)[0], array_values($value)[0]);
            } else {
                foreach ($value as $secondItem) {
                    $queryObject->where($key, array_keys($secondItem)[0], array_values($secondItem)[0]);
                }
            }
        } else {
            $queryObject->where($key, $value);
        }
    }
}
