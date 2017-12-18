<?php

namespace Hiland\Utils\DataBase;

use Hiland\Utils\Web\ClientHelper;

class DAO
{
    // 数据库主机名称
    protected $dbHostName;
    // 数据库主机端口
    protected $dbHostPort;
    // 数据库用户名
    protected $dbUserName;
    // 数据库用户名密码
    protected $dbPassword;
    // 数据库名
    protected $dbDataBase;
    // 数据库连接标识
    protected $connection;
    // 数据库连接类型
    protected $connectionType;
    // 执行query命令的结果资源标识
    protected $result;
    // sql执行语句
    protected $sql;
    // 返回的条目数
    protected $row;
    // 数据库编码，GBK,UTF8,gb2312
    protected $coding;
    // 是否开启错误记录
    protected $isLogError = false;
    // 测试阶段，显示所有错误,具有安全隐患,默认关闭
    protected $showError = false;
    // 发现错误是否立即终止,默认true,建议不启用，因为当有问题时用户什么也看不到是很苦恼的
    protected $isHaltWhenError = false;

    /**
     * 构造函数
     *
     * @param string $dbHostName
     * @param int $dbHostPort
     * @param string $dbUserName
     * @param string $dbPassword
     * @param string $dbDataBase
     * @param string $connectionType
     *            设置为'pconn'的时候为长连接（仅在mysql驱动下有效）
     * @param string $coding
     */
    public function __construct($dbHostName, $dbHostPort, $dbUserName, $dbPassword, $dbDataBase, $connectionType = '', $coding = 'UTF8')
    {
        $this->dbHostName = $dbHostName;
        $this->dbHostPort = $dbHostPort;
        $this->dbUserName = $dbUserName;
        $this->dbPassword = $dbPassword;
        $this->dbDataBase = $dbDataBase;
        $this->connectionType = $connectionType;
        $this->coding = $coding;
        $this->connect();
    }

    /* 数据库连接 */
    public function connect()
    {
        $this->connectInner();
    }

    protected function connectInner()
    {
        // 1.设置连接

        // 2.设置数据库字符编码
    }

    /* 数据库执行语句，可执行查询添加修改删除等任何sql语句 */

    public function showTableNames($dataBaseName = '')
    {
        if (empty($dataBaseName)) {
            $dataBaseName = $this->dbDataBase;
        }

        $result = $this->getTableNames($dataBaseName);
        $amount = count($result);
        echo "现有数据库[$dataBaseName],共有表：[$amount]个";
        echo "<br />";

        foreach ($result as $key => $value) {
            echo $key + 1 . ':' . $value;
            echo "<br />";
        }
    }

    /**
     * 获取数据库下所有的表名称
     * @param string $dataBaseName
     * @return string
     */
    public function getTableNames($dataBaseName = '')
    {
        if (empty($dataBaseName)) {
            $dataBaseName = $this->dbDataBase;
        }

        $tables = $this->getTables($dataBaseName);

        $result = '';
        foreach ($tables as $key => $value) {
            $columnName = "Tables_in_" . $dataBaseName;
            $result[$key] = $value[$columnName];
        }

        return $result;
    }

    /* 查询数据库下所有的表 */

    /**
     * 获取数据库下所有的表名称
     * @param string $dataBaseName
     * @return string
     */
    public function getTables($dataBaseName = '')
    {
        if (empty($dataBaseName)) {
            $dataBaseName = $this->dbDataBase;
        }

        $i = 0;
        $result = '';

        $rs = $this->query("show tables");
        while ($row = $this->fetchArray($rs)) {
            $result[$i] = $row;
            $i++;
        }

        return $result;
    }

    /**
     * @param $sql
     * @return bool|void
     */
    public function query($sql)
    {
        if (empty($sql)) {
            $this->showError("SQL语句错误：", "SQL查询语句为空");
            return false;
        } else {
            $this->sql = $sql;
        }

        $result = $this->queryInner();

        if ($result) {
            $this->result = $result;
            return $result;
        } else {
            // 调试中使用，sql语句出错时会自动打印出来
            if ($this->showError) {
                $this->showError("错误SQL语句：", $this->sql);
            }

            return false;
        }
    }

    /**
     * @param string $message
     * @param string $sql
     */
    public function showError($message = "", $sql = "")
    {
        if (!$sql) {
            echo "<font color='red'>" . $message . "</font>";
            echo "<br />";
        } else {
            echo "<fieldset>";
            echo "<legend>错误信息提示:</legend><br />";
            echo "<div style='font-size:14px; clear:both; font-family:Verdana, Arial, Helvetica, sans-serif;'>";
            echo "<div style='height:20px; background:#000000; border:1px #000000 solid'>";
            echo "<font color='white'>错误号：12142</font>";
            echo "</div><br />";
            echo "错误原因：" . $this->getMysqlInnerError() . "<br /><br />";
            echo "<div style='height:20px; background:#FF0000; border:1px #FF0000 solid'>";
            echo "<font color='white'>" . $message . "</font>";
            echo "</div>";
            echo "<font color='red'><pre>" . $sql . "</pre></font>";
            $ip = ClientHelper::getOnlineIP(); // $this->getip();
            if ($this->isLogError) {
                $time = date("Y-m-d H:i:s");
                $message = $message . "\r\n$this->sql" . "\r\n客户IP:$ip" . "\r\n时间 :$time" . "\r\n\r\n";

                $server_date = date("Y-m-d");
                $filename = $server_date . ".txt";
                $file_path = "error/" . $filename;
                $error_content = $message;
                // $error_content="错误的数据库，不可以链接";
                $file = "error"; // 设置文件保存目录

                // 建立文件夹
                if (!file_exists($file)) {
                    if (!mkdir($file, 0777)) {
                        // 默认的 mode 是 0777，意味着最大可能的访问权
                        die("upload files directory does not exist and creation failed");
                    }
                }

                // 建立txt日期文件
                if (!file_exists($file_path)) {

                    // echo "建立日期文件";
                    fopen($file_path, "w+");

                    // 首先要确定文件存在并且可写
                    if (is_writable($file_path)) {
                        // 使用添加模式打开$filename，文件指针将会在文件的开头
                        if (!$handle = fopen($file_path, 'a')) {
                            echo "不能打开文件 $filename";
                            exit();
                        }

                        // 将$somecontent写入到我们打开的文件中。
                        if (!fwrite($handle, $error_content)) {
                            echo "不能写入到文件 $filename";
                            exit();
                        }

                        // echo "文件 $filename 写入成功";

                        echo "——错误记录被保存!";

                        // 关闭文件
                        fclose($handle);
                    } else {
                        echo "文件 $filename 不可写";
                    }
                } else {
                    // 首先要确定文件存在并且可写
                    if (is_writable($file_path)) {
                        // 使用添加模式打开$filename，文件指针将会在文件的开头
                        if (!$handle = fopen($file_path, 'a')) {
                            echo "不能打开文件 $filename";
                            exit();
                        }

                        // 将$somecontent写入到我们打开的文件中。
                        if (!fwrite($handle, $error_content)) {
                            echo "不能写入到文件 $filename";
                            exit();
                        }

                        // echo "文件 $filename 写入成功";
                        echo "——错误记录被保存!";

                        // 关闭文件
                        fclose($handle);
                    } else {
                        echo "文件 $filename 不可写";
                    }
                }
            }
            echo "<br />";
            if ($this->isHaltWhenError) {
                exit();
            }
        }
        echo "</div>";
        echo "</fieldset>";

        echo "<br />";
    }

    // 查询字段数量

    /**
     * 获取mysql服务器内部错误
     */
    public function getMysqlInnerError()
    {
        return $this->getMysqlInnerErrorInner();
    }

    protected function getMysqlInnerErrorInner()
    {
        //
    }

    protected function queryInner()
    {
        //
    }

    /**
     *
     * @param array $result
     * @param int $fetchType
     *            取值为：
     *            1 或者MYSQL_ASSOC、MYSQLI_ASSOC - 关联数组，用字段名称表示Key的数组
     *            2 或者MYSQL_NUM、MYSQLI_NUM - 数字数组，用数字表示Key的数组
     *            3 或者MYSQL_BOTH、MYSQLI_BOTH - 同时产生关联和数字数组
     */
    public function fetchArray($result = null, $fetchType = 3)
    {
        if (empty($result)) {
            $result = $this->result;
        }

        return $this->fetchArrayInner($result, $fetchType);
    }

    /*
     * mysql_fetch_row() array $row[0],$row[1],$row[2]
     * mysql_fetch_array() array $row[0] 或 $row[id]
     * mysql_fetch_assoc() array 用$row->content 字段大小写敏感
     * mysql_fetch_object() object 用$row[id],$row[content] 字段大小写敏感
     */

    /* 取得记录集,获取数组-索引和关联,使用$row['content'] */

    protected function fetchArrayInner($result, $fetchType = 3)
    {
        // return mysql_fetch_array($result);
    }

    public function showFieldNames($tableName)
    {
        $result = $this->getFieldNames($tableName);
        echo "<br />";
        echo "字段数：" . $total = count($result);
        echo "<pre>";
        for ($i = 0; $i < $total; $i++) {
            echo($result[$i] . '<br/>');
        }
        echo "</pre>";
        echo "<br />";
    }

    // 获取关联数组,使用$row['字段名']

    /**
     * @param $tableName
     * @return string
     */
    public function getFieldNames($tableName)
    {
        $queryResult = $this->getFields($tableName);
        $total = count($queryResult);

        $result = '';
        for ($i = 0; $i < $total; $i++) {
            $result[$i] = $queryResult[$i]->name;
        }
        return $result;
    }

    /**
     * @param $tableName
     * @return array
     */
    public function getFields($tableName)
    {
        $queryResult = $this->query("select * from $tableName LIMIT 1");
        return $this->getFieldsInner($queryResult);
    }

    // 获取数字索引数组,使用$row[0],$row[1],$row[2]

    protected function getFieldsInner($queryResult)
    {
        //
    }

    public function fetchAssoc($result = null)
    {
        if (empty($result)) {
            $result = $this->result;
        }
        return $this->fetchAssocInner($result);
    }

    // 获取对象数组,使用$row->content

    protected function fetchAssocInner($result)
    {
        // return mysql_fetch_assoc($result);
    }

    public function fetchRow($result = null)
    {
        if (empty($result)) {
            $result = $this->result;
        }

        return $this->fetchRowInner($result); // mysql_fetch_row($result);
    }

    // 简化查询select

    protected function fetchRowInner($result = null)
    {
        // return mysql_fetch_row($result);
    }

    // 简化查询select

    public function fetchObject($result = null)
    {
        if (empty($result)) {
            $result = $this->result;
        }
        return $this->fetchObjectInner($result);
    }

    // 简化删除del

    protected function fetchObjectInner($result)
    {
        // return mysql_fetch_object($result);
    }

    // 简化插入insert

    public function findAll($table)
    {
        $this->query("SELECT * FROM $table");
    }

    // 简化修改update

    public function select($table, $columnName = "*", $condition = '', $debug = '')
    {
        $condition = $condition ? ' Where ' . $condition : NULL;
        if ($debug) {
            echo "SELECT $columnName FROM $table $condition";
        } else {
            $this->query("SELECT $columnName FROM $table $condition");
        }
    }

    /* 取得上一步 INSERT 操作产生的 ID */

    public function delete($table, $condition, $url = '')
    {
        if ($this->query("DELETE FROM $table WHERE $condition")) {
            if (!empty($url))
                $this->Get_admin_msg($url, '删除成功！');
        }
    }

    public function insert($table, $columnName, $value, $url = '')
    {
        if ($this->query("INSERT INTO $table ($columnName) VALUES ($value)")) {
            if (!empty($url))
                $this->Get_admin_msg($url, '添加成功！');
        }
    }

    // 根据select查询结果计算结果集条数

    public function update($table, $mod_content, $condition, $url = '')
    {
        // echo "UPDATE $table SET $mod_content WHERE $condition"; exit();
        if ($this->query("UPDATE $table SET $mod_content WHERE $condition")) {
            if (!empty($url))
                $this->Get_admin_msg($url);
        }
    }

    public function getLastInsertedID()
    {
        return $this->getLastInsertedIDInner();
    }

    // 根据insert,update,delete执行结果取得影响行数

    protected function getLastInsertedIDInner()
    {
        // return mysql_insert_id();
    }

    public function getResultRowCount($result = null)
    {
        if (empty($result)) {
            $result = $this->result;
        }

        if ($result == null) {
            if ($this->showError) {
                $this->showError("SQL语句错误", "暂时为空，没有任何内容！");
            }
        } else {
            return $this->getResultRowCountInner($result);
        }
    }

    protected function getResultRowCountInner($result)
    {
        //
    }

    public function getAffectedRowCount()
    {
        return $this->getAffectedRowCountInner(); // mysql_affected_rows();
    }

    // 输出显示sql语句

    protected function getAffectedRowCountInner()
    {
        // return mysql_affected_rows();
    }

    // 析构函数，自动关闭数据库,垃圾回收机制

    public function __destruct()
    {
        $this->destructInner();
    }

    protected function destructInner()
    {
        // 1、释放结果集

        // 2、关闭连接
    }

    function injectCheck($sql_str)
    { // 防止注入
        $check = eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);
        if ($check) {
            echo "输入非法注入内容！";
            exit();
        } else {
            return $sql_str;
        }
    }

    function checkUrl()
    { // 检查来路
        if (preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
            header("Location: http://www.dareng.com");
            exit();
        }
    }
}