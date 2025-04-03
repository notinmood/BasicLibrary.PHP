<?php
/**
 * @file   : SystemEnum.php
 * @time   : 9:09
 * @date   : 2022/1/5
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\DataValue;

/**
 * 系统使用到的"枚举"类型
 */
class SystemEnum
{
    //-- 随机数类型 -- Utils/Data/RandHelper.php
    public const RandCategory_LETTER  = "LETTER";
    public const RandCategory_NUMBER  = "NUMBER";
    public const RandCategory_SPECIAL = "SPECIAL";
    public const RandCategory_ALL     = "ALL";
    public const RandCategory_OTHER   = "OTHER";

    //-- Where条件的连接字符 -- Utils/DataModel/ModelMate.php
    public const WhereConnector_AND = "__WHERE_AND__";
    public const WhereConnector_OR  = "__WHERE_OR__";
}
