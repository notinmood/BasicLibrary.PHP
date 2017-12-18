<?php

namespace Hiland\Utils\DataBase;

class Mysqli extends DAO
{
    protected function connectInner()
    {
        // 1.设置连接
        $this->connection = mysqli_init();
        $this->connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5); // 设置超时时间
        $this->connection->real_connect($this->dbHostName, $this->dbUserName, $this->dbPassword, $this->dbDataBase, $this->dbHostPort);

        // 用函数来判断是否连接成功
        if (mysqli_connect_errno()) {
            if ($this->showError) {
                $this->showError("连接数据库失败：", $this->dbDataBase);
            }
        }

        // 2.设置数据库字符编码
        mysqli_query($this->connection, "SET NAMES $this->coding");
    }

    protected function queryInner()
    {
        $result = mysqli_query($this->connection, $this->sql);
        return $result;
    }

    protected function fetchArrayInner($result, $fetchType = MYSQLI_BOTH)
    {
        return mysqli_fetch_array($result, $fetchType);
    }

    protected function fetchAssocInner($result)
    {
        return mysqli_fetch_assoc($result);
    }

    protected function fetchRowInner($result)
    {
        return mysqli_fetch_row($result);
    }

    protected function fetchObjectInner($result)
    {
        return mysqli_fetch_object($result);
    }

    /**
     * @return int
     */
    protected function getAffectedRowCountInner()
    {
        return mysqli_affected_rows($this->connection);
    }

    protected function getResultRowCountInner($result)
    {
        return mysqli_num_rows($result);
    }

    protected function getLastInsertedIDInner()
    {
        return mysqli_insert_id($this->connection);
    }

    protected function destructInner()
    {
        // 1、释放结果集
        if (!empty($this->result)) {
            @ mysqli_free_result($this->result);
        }

        // 2、关闭连接
        mysqli_close($this->connection);
    }

    protected function getMysqlInnerErrorInner()
    {
        return mysqli_error($this->connection);
    }

    protected function getFieldsInner($queryResult)
    {
        $total = mysqli_num_fields($queryResult);

        $result = '';
        for ($i = 0; $i < $total; $i++) {
            $fieldInfo = mysqli_fetch_field($queryResult);
            $result[$i] = $fieldInfo;
        }
        return $result;
    }
}