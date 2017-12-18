<?php

namespace Hiland\Utils\DataBase;

/**
 *
 * @author devel
 *
 */
class Mysql extends DAO
{
    protected function connectInner()
    {
        $dbHost = $this->dbHostName . ":" . $this->dbHostPort;

        // 1.设置连接
        if ($this->connectionType == "pconn") {
            // 永久链接
            $this->connection = mysql_pconnect($dbHost, $this->dbUserName, $this->dbPassword);
        } else {
            // 即使链接
            $this->connection = mysql_connect($dbHost, $this->dbUserName, $this->dbPassword);
        }

        if (!mysql_select_db($this->dbDataBase, $this->connection)) {
            if ($this->showError) {
                $this->showError("数据库不可用：", $this->dbDataBase);
            }
        }

        // 2.设置数据库字符编码
        mysql_query("SET NAMES $this->coding");
    }

    protected function queryInner()
    {
        $result = mysql_query($this->sql, $this->connection);
        return $result;
    }

    protected function fetchArrayInner($result, $fetchType = MYSQL_BOTH)
    {
        return mysql_fetch_array($result, $fetchType);
    }

    protected function fetchAssocInner($result)
    {
        return mysql_fetch_assoc($result);
    }

    protected function fetchRowInner($result)
    {
        return mysql_fetch_row($result);
    }

    protected function fetchObjectInner($result)
    {
        return mysql_fetch_object($result);
    }

    protected function getAffectedRowCountInner()
    {
        return mysql_affected_rows();
    }

    protected function getResultRowCountInner($result)
    {
        return mysql_num_rows($result);
    }

    protected function getLastInsertedIDInner()
    {
        return mysql_insert_id();
    }

    protected function destructInner()
    {
        // 1、释放结果集
        if (!empty($this->result)) {
            @ mysql_free_result($this->result);
        }

        // 2、关闭连接
        mysql_close($this->connection);
    }

    protected function getMysqlInnerErrorInner()
    {
        return mysql_error();
    }

    protected function getFieldsInner($queryResult)
    {
        $total = mysql_num_fields($queryResult);

        $result = '';
        for ($i = 0; $i < $total; $i++) {
            $fieldInfo = mysql_fetch_field($queryResult, $i);
            $result[$i] = $fieldInfo;
        }
        return $result;
    }
}