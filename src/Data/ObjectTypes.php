<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2016/8/18
 * Time: 17:38
 */

namespace Hiland\Data;

/**
 *
 */
class ObjectTypes
{
    public const STRING   = "string";
    public const INTEGER  = "integer";
    public const BOOLEAN  = "boolean";

    //double是float的别名，为了兼容php5.x
    public const DOUBLE   = "double";
    public const FLOAT    = "float";
    public const RESOURCE = "resource";
    public const ARRAY   = "array";
    public const OBJECT   = "object";
    public const NULL     = "null";
    public const DATETIME = "datetime";
    public const CLOSURE  = "Closure";
}
