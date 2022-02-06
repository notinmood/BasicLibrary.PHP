<?php
/**
 * @file   : DatabaseClient.php
 * @time   : 9:49
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataModel;

/**
 * 数据库向外暴露的唯一接口
 */
class DatabaseClient
{
    /**
     * 获取数据库操作 Mate(对数据库表内的数据进行操作)
     * (目前直接是从Container 获取数据；以后加入多类型数据库的时候，会加入数据库类型的判断与加载)
     * @param $tableName
     * @return ModelMate
     */
    public static function getMate($tableName): ModelMate
    {
        return MateContainer::get($tableName);
    }

    /**
     * 获取数据库操作 DDL(对数据库表结构进行操作)
     * @return ModelDDL
     */
    public static function getDDL(): ModelDDL
    {
        $ddlKey = "__database_ddl__";
        $ddl = Container::get($ddlKey);

        if ($ddl == null) {
            $ddl = new ModelDDL();
            Container::set($ddlKey, $ddl);
        }

        return $ddl;
    }
}