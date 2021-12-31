说明
--
1. 为了能够直接执行 sql 语句，需要在文件 vendor/topthink/think-orm/src/db/Query.php 内增加以下两个方法
   (TODO:需要改进，把以下两个方法单独包装到bl.php内，供其他地方调用)
```shell
    /**
     * 执行查询 返回数据集
     * @access public
     * @param string      $sql    sql指令
     * @param array       $bind   参数绑定
     * @param boolean     $master 是否在主服务器读操作
     * @param bool|string $class  指定返回的数据集对象
     * @return mixed
     * @throws BindParamException
     * @throws PDOException
     */
    public function query($sql, $bind = [], $master = false, $class = false)
    {
        return $this->connection->query($sql, $bind, $master, $class);
    }

    /**
     * 执行语句
     * @access public
     * @param string $sql  sql指令
     * @param array  $bind 参数绑定
     * @return int
     * @throws BindParamException
     * @throws PDOException
     */
    public function execute($sql, $bind = [])
    {
        return $this->connection->execute($sql, $bind);
    }
```