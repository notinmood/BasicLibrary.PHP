<?php


namespace Hiland\Utils\Data;


class TypeHelper
{
    const STRING = "string";
    const INTEGER = "integer";
    const BOOLEAN = "boolean";
    const DOUBLE = "double";
    const FLOAT = "float";
    const RESOURCE = "resource";
    const ARRAY = "array";
    const OBJECT = "object";
    const NULL = "NULL";
    const DATETIME = "datetime";


    public static function getTypeName($value)
    {
        $typeName = gettype($value);
        switch ($typeName) {
            case "string":
                return self::STRING;
            case "integer":
                return self::INTEGER;
            case "boolean":
                return self::BOOLEAN;
            case "double":
                return self::DOUBLE;
            case "float":
                return self::FLOAT;
            case "resource":
                return self::RESOURCE;
            case "array":
                return self::ARRAY;
            case "object":
                return self::OBJECT;
            case "datetime":
                return self::DATETIME;
            default :
                return self::NULL;
        }
    }
}