<?php
/**
 * @file   : SystemEnum.php
 * @time   : 9:09
 * @date   : 2022/1/5
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\DataValue;

/**
 * 系统使用到的"枚举"类型
 */
class SystemEnum
{
    //-- 随机数类型 -- Utils/Data/RandHelper.php
    const RandCategory_LETTER = "LETTER";
    const RandCategory_NUMBER = "NUMBER";
    const RandCategory_SPECIAL = "SPECIAL";
    const RandCategory_ALL = "ALL";
    const RandCategory_OTHER = "OTHER";

    //-- Where条件的连接字符 -- Utils/DataModel/ModelMate.php
    const WhereConnector_AND = "__WHERE_AND__";
    const WhereConnector_OR = "__WHERE_OR__";
}