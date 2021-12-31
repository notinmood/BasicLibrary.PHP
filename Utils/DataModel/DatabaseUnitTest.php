<?php
/**
 * @file   : DatabaseUnitTest.php
 * @time   : 17:00
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

class DatabaseUnitTest
{
    /**
     * @var bool|mixed
     */
    private $autoDispose;
    /**
     * @var string
     */
    private $newTableName;
    /**
     * @var ModelMate
     */
    private $mate;
    /**
     * @var ModelDDL
     */
    private $ddl;

    public function __construct($tableName, $duplicateRowCount = -1, $autoDispose = True)
    {
        $this->autoDispose = $autoDispose;
        $this->newTableName = $tableName . "__tmp_dup__";

        $this->ddl = DatabaseClient::getDDL();
        $this->ddl->duplicateTable($tableName, $this->newTableName, $duplicateRowCount);

        $this->mate = DatabaseClient::getMate($this->newTableName);
    }

    public function getNewTableName()
    {
        return $this->newTableName;
    }

    public function getDDL()
    {
        return $this->ddl;
    }

    public function getMate()
    {
        return $this->mate;
    }

    /**
     * 在析构函数里面，销毁创建的各种资源
     */
    public function __destruct()
    {
        if ($this->autoDispose) {
            $this->dispose();
        }
    }

    /**
     * 销毁创建的各种资源
     * @return void
     */
    public function dispose()
    {
        $this->ddl->dropTable($this->newTableName);
    }
}