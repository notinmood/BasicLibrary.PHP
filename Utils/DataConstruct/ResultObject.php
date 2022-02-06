<?php

namespace Hiland\Utils\DataConstruct;

use ArrayAccess;
use Hiland\Utils\Data\ObjectHelper;
use stdClass;

/**
 * 返回值对象
 * ────────────────────────
 * 类型包括 3个常用的属性和一个备用属性
 * 1. status, bool类型,表示返回的结果是成功还是失败
 * 2. message, string类型,表示返回信息的文本描述
 * 3. data, mixed类型,表示返回信息的具体信息
 * 4. 其他要返回的信息，统一通过动态成员的方式放入标准对象misc里面，如下：
 *  $r = new Returns(true, "very good!", "这个人还不错");
 *  $r->misc->pa = 'something';(其中 pa 就是 misc 的动态成员)
 *  或者
 *  $r["pa"] = 'something';(使用 ArrayAccess 方式访问动态成员)
 */
class ResultObject implements ArrayAccess
{
    public function __construct($status, $message, $data = null)
    {
        $this->status  = $status;
        $this->message = $message;
        $this->data    = $data;
        $this->misc    = new stdClass();
    }

    public bool      $status  = true;
    public string    $message = "";
    public           $data    = null;
    public ?stdClass $misc    = null;

    /**
     * 设定属性 misc 的各个子属性
     * @param {*} name
     * @param {*} value
     */
    public function setMiscItem($name, $value)
    {
        $this->misc->$name = $value;
    }

    /**
     * 获取 misc 的各个子属性的值
     * @param {*} name
     * @param {*} defaultValue
     */
    public function getMiscItem($name, $defaultValue = null)
    {
        return ObjectHelper::getMember($this->misc, $name, $defaultValue);
    }

    /**
     * (为便于传递)将返回值对象进行字符串化
     * @param ResultObject $resultObject
     * @return string
     */
    public static function stringify(ResultObject $resultObject): string
    {
        return json_encode($resultObject);
    }


    /**
     * 对给定格式的字符串进行解析，得到结构化表示的 Returns 对象
     * @param string $stringData 给定json格式的字符串
     * @return ResultObject 结构化表示的返回值对象
     */
    public static function parse(string $stringData): ResultObject
    {
        $jsonObject = json_decode($stringData);

        $type    = $jsonObject->status;
        $message = $jsonObject->message;
        $data    = $jsonObject->data;
        $misc    = $jsonObject->misc;

        $result       = new ResultObject($type, $message, $data);
        $result->misc = $misc;
        return $result;
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
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
        $result = ObjectHelper::getMember($this, $offset);
        if (ObjectHelper::isNull($result)) {
            $result = ObjectHelper::getMember($this->misc, $offset);
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
