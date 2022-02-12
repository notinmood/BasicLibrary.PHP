<?php
/**
 * @file   : NullObject.php
 * @time   : 10:04
 * @date   : 2021/10/12
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Utils\Pattern;

use Exception;

/**
 * 空对象
 * ════════════════════════
 * 本对象的应用场景为在业务逻辑的返回值中,以此来减少在客户代码中对null的判断
 * https://www.imooc.com/article/17852
 */
class NullObject
{
    const NON = "non";
    const ERROR = "error";
    const TIP = "tip";
    const EXCEPTION = "exception";

    private $actionLevel = 0;

    public function __construct($actionLevel = self::NON)
    {
        $this->actionLevel = $actionLevel;
    }

    /**
     * @param $method
     * @param $arg
     * @throws Exception
     */
    public function __call($method, $arg)
    {
        $message = "当前为一个空对象,在其上调用方法 {$method} 没有任何效果";
        switch ($this->actionLevel) {
            case self::NON:
                // do nothing;
                break;
            case self::TIP:
                echo $message;
                break;
            case self::ERROR:
            case self::EXCEPTION:
            default:
                throw new Exception($message);
        }
    }
}