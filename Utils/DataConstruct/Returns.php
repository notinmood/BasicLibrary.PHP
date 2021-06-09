<?php


namespace Hiland\Utils\DataConstruct;


use Hiland\Utils\Data\BoolHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\ObjectTypes;
use Hiland\Utils\Data\StringHelper;

/**类型包括3个常用的属性和一个备用属性
 * 1.resultType,bool类型,表示返回的结果是成功还是失败
 * 2.title, string类型,表示
 * 3.desc,string类型,表示返回信息的具体描述
 * 4.其他要返回的信息，统一通过动态成员的方式放入标准对象misc里面，如下：
 *  $r = new Returns(true, "very good!", "这个人还不错");
 *  $r->misc->pa = 'something';(其中pa就是misc的动态成员)
 *
 * Class Returns
 * @package Hiland\Utils\DataConstruct
 */
class Returns
{
    public function __construct($resultType, $title, $desc = "")
    {
        $this->resultType = $resultType;
        $this->title = $title;
        $this->desc = $desc;
        $this->misc = new \stdClass();
    }

    public $resultType = true;
    public $title = "";
    public $desc = "";
    public $misc = null;

    /**
     * @param Returns $returnObject
     * @return string
     */
    public static function compose($returnObject)
    {
        return json_encode($returnObject);
    }


    /** 对给定格式的字符串进行解析，得到结构化表示的Returns对象
     * @param string $dataString 给定json格式的字符串
     * @return Returns 结构化表示的Returns对象
     */
    public static function resolve($dataString)
    {
        $stdObject = json_decode($dataString);

        $type = $stdObject->resultType;
        $title = $stdObject->title;
        $desc = $stdObject->desc;
        $misc = $stdObject->misc;

        $result = new Returns($type, $title, $desc);
        $result->misc = $misc;
        return $result;
    }
}