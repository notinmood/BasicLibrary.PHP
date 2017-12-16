<?php
namespace Hiland\Utils\Data;

class ExtentibleRepository
{

    private $keys = '';
    private $values = '';
    private $isParserd = false;

    // / <summary>
    // / 传递进来的（用逗号分隔的字符串集合类型的）名称和值是否解析进入NameValueCollection内。
    // / （为了提高系统的性能，扩展属性部分采用了延迟加载，即有请求才加载。本字段标明扩展属性是否经过解析和加载）
    // / </summary>
    /**
     * 保存名值对的数组
     * @var array
     */
    private $nvc;

    /**
     * 构造函数
     *
     * @param string $keys
     * @param mixed $values
     */
    public function __construct($keys, $values)
    {
        $this->keys = $keys;
        $this->values = $values;
    }

    /**
     * 获取可扩展属性的值
     * @param string $name 属性名称
     * @return string 属性值
     */
    public function GetExtentibleProperty($name)
    {
        $this->GetNVC();

        $returnValue = null;

        if (array_key_exists($name, $this->nvc)) {
            $returnValue = $this->nvc[$name];
        }

        return $returnValue;
    }

    // / <summary>
    // / 设置可扩展属性的值
    // / </summary>
    // / <param name="settingName"></param>
    // / <param name="name"></param>
    // / <param name="value"></param>

    private function GetNVC()
    {
        if ($this->isParserd == false) {
            $this->nvc = self::ConvertToNameValueCollection($this->keys, $this->values);
            $this->isParserd = true;
        }
    }

    /**
     * 将序列化进入keys和values的数据，转换为名值对数组
     * @param string $keys
     * @param mixed $values
     * @return string
     * @example string keys = "key1:S:0:3:key2:S:3:2:";
     *          string values = "12345";
     *          This would result in a NameValueCollection with two keys (Key1 and Key2) with the values 123 and 45
     */
    private static function convertToNameValueCollection($keys, $values)
    {
        $nvc = array();

        if ($keys != null && $values != null && strlen($keys) > 0 && strlen($values) > 0) {

            $keyNames = explode(':', $keys);
            $keyCount = (count($keyNames));

            for ($i = 0; $i < ($keyCount / 4); $i++) {
                $start = (int)($keyNames[($i * 4) + 2]);
                $len = (int)($keyNames[($i * 4) + 3]);
                $key = $keyNames[$i * 4];

                // Future version will support more complex types
                if ((($keyNames[($i * 4) + 1] == "S") && ($start >= 0)) && ($len > 0) && (strlen($values) >= ($start + $len))) {
                    $nvc[$key] = substr($values, $start, $len);
                }
            }
        }

        return $nvc;
    }

    public function SetExtentibleProperty($name, $value)
    {
        $this->GetNVC();

        if ($value == null) {
            unset($this->nvc[$name]);
        } else {
            $this->nvc[$name] = $value;
        }
    }

    // / <summary>
    // / 获取指定的NameValueCollection(如果字典中不存在此NVC，则同时创建)
    // / </summary>
    // / <param name="settingName">NameValueCollection的名称</param>
    // / <returns></returns>

    /**
     * 获取可扩展属性的数量
     */
    public function GetExtentiblePropertyCount()
    {
        $this->GetNVC();
        return count($this->nvc);
    }

    public function Serialize()
    {
        $result = self::convertToSerializerData($this->nvc);
        return $result;
    }

    /**
     * 转换为可序列化的数据
     * @param array $nvc 各个属性的名值对数组
     * @throws \Exception
     * @return string[] 数组两个参数分别为，'keys':所有的键名的信息；'values':所有值的信息
     */
    private static function convertToSerializerData($nvc)
    {
        $sbKey = '';
        $sbValue = '';

        if ($nvc != null && count($nvc) > 0) {
            $index = 0;
            foreach ($nvc as $key => $value) {
                if (StringHelper::isContains($key, ':')) {
                    throw new \Exception("ExtendedAttributes Key can not contain the character \":\"");
                }

                if (!empty($value)) {
                    $valuelength = strlen($value);
                    $sbKey .= "$key:S:$index:$valuelength:";
                    $sbValue .= $value;
                    $index += $valuelength;
                }
            }
        }

        return array(
            'keys' => $sbKey,
            'values' => $sbValue
        );
    }
}

?>