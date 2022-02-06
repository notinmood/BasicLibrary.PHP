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

use Hiland\Utils\Data\RandHelper;
use Hiland\Utils\DataValue\SystemEnum;

class DatabaseUnitTest
{
    /**
     * @var bool|mixed
     */
    private $autoDispose;
    /**
     * @var string
     */
    private string $newTableName;
    /**
     * @var ModelMate
     */
    private ModelMate $mate;
    /**
     * @var ModelDDL
     */
    private ModelDDL $ddl;

    public function __construct($tableName, $duplicateRowCount = -1, $autoDispose = True)
    {
        $this->autoDispose = $autoDispose;
        $random = RandHelper::get(8, SystemEnum::RandCategory_NUMBER);
        $this->newTableName = $tableName . "__dup_{$random}__";

        $this->ddl = DatabaseClient::getDDL();
        $this->ddl->duplicateTable($tableName, $this->newTableName, $duplicateRowCount);

        $this->mate = DatabaseClient::getMate($this->newTableName);
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

    /**
     * 获取复制的新表的表名称
     * @return string
     */
    public function getNewTableName(): string
    {
        return $this->newTableName;
    }

    public function getDDL(): ModelDDL
    {
        return $this->ddl;
    }

    public function getMate(): ModelMate
    {
        return $this->mate;
    }
}