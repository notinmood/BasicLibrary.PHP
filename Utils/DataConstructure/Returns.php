<?php


namespace Hiland\Utils\DataConstructure;


use Hiland\Utils\Data\BoolHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\ObjectTypes;
use Hiland\Utils\Data\StringHelper;

class Returns
{
    public function __construct($resultType, $title, $desc = "")
    {
        $this->resultType = $resultType;
        $this->title = $title;
        $this->desc = $desc;
    }

    public $resultType = true;
    public $title = "";
    public $desc = "";

    /**
     * @param bool $resultType
     * @param string $title
     * @param string $desc
     * @return string
     */
    public static function compose($resultType, $title, $desc = "")
    {
        $type = ObjectHelper::getType($resultType);
        if ($type == ObjectTypes::BOOLEAN) {
            $resultType = BoolHelper::getText($resultType);
        }

        $data = "{?}_||_{?}_||_{?}";
        return StringHelper::format($data, [$resultType, $title, $desc]);
    }


    /** 对给定格式的字符串，进行解析，得到结构化表示的Returns对象
     * @param string $data 给定格式的字符串,类似"true_||_hello_||_box"
     * @return Returns 结构化表示的Returns对象
     */
    public static function resolve($data)
    {
        $midArray = StringHelper::explode($data, "_||_");
        if ($midArray && count($midArray) >= 3) {
            $type = BoolHelper::isTrue($midArray[0]);
            return new Returns($type,$midArray[1],$midArray[2]);
        } else {
            return $data;
        }
    }
}