<?php

namespace Hiland\Utils\DataConstruct;

use Hiland\Utils\Data\ObjectHelper;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\TentativeType;
use function PHPUnit\Framework\isNull;

/**类型包括3个常用的属性和一个备用属性
 * 1.status, bool类型,表示返回的结果是成功还是失败
 * 2.message, string类型,表示返回信息的文本描述
 * 3.data, mixed类型,表示返回信息的具体信息
 * 4.其他要返回的信息，统一通过动态成员的方式放入标准对象misc里面，如下：
 *  $r = new Returns(true, "very good!", "这个人还不错");
 *  $r->misc->pa = 'something';(其中pa就是misc的动态成员)
 * Class ResultObject
 * @package Hiland\Utils\DataConstruct
 */
class ResultObject implements \ArrayAccess
{
    public function __construct($status, $message, $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->misc = new \stdClass();
    }

    public $status = true;
    public $message = "";
    public $data = null;
    public $misc = null;

    /**
     * 设定属性misc的各个子属性
     * @param {*} name
     * @param {*} value
     */
    public function setMiscItem($name, $value)
    {
        $this->misc->$name = $value;
    }

    /**
     * 获取misc的各个子属性的值
     * @param {*} name
     * @param {*} defaultValue
     */
    public function getMiscItem($name, $defaultValue = null)
    {
        return ObjectHelper::getMember($this->misc, $name, $defaultValue);
    }

    /**
     * @param ResultObject $resultObject
     * @return string
     */
    public static function stringify($resultObject)
    {
        return json_encode($resultObject);
    }


    /** 对给定格式的字符串进行解析，得到结构化表示的Returns对象
     * @param string $stringData 给定json格式的字符串
     * @return ResultObject 结构化表示的返回值对象
     */
    public static function parse($stringData)
    {
        $jsonObject = json_decode($stringData);

        $type = $jsonObject->status;
        $message = $jsonObject->message;
        $data = $jsonObject->data;
        $misc = $jsonObject->misc;

        $result = new ResultObject($type, $message, $data);
        $result->misc = $misc;
        return $result;
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $result = ObjectHelper::isMember($this, $offset);
        if (!$result) {
            $result = ObjectHelper::isMember($this->misc, $offset);
        }

        return $result;
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $result = ObjectHelper::getMember($this, $offset, null);
        if (ObjectHelper::isNull($result)) {
            $result = ObjectHelper::getMember($this->misc, $offset, null);
        }

        return $result;
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $isMember = ObjectHelper::isMember($this, $offset);
        if ($isMember) {
            $this->$offset = $value;
        } else {
            $this->setMiscItem($offset, $value);
        }
    }

    /**
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $isMember = ObjectHelper::isMember($this, $offset);
        if ($isMember) {
            $this->$offset = null;
        } else {
            unset($this->misc->$offset);
        }
    }
}
