<?php
namespace Hiland\Utils\Data;

use Think\Model;

class OperationHelper
{

    /**
     * 从系统各种类型的返回值中判断，执行成功与否
     *
     * @param mixed $operationResult
     *            系统中约定：
     *            执行成功返回true或者数字（表示数据库执行成功的条数，被影响表记录的id等）；
     *            执行失败返回false或者0,null,字符串（表示失败的原因）
     * @return bool 成败标志
     *         各种表示成功的都用true表示
     *         各种表示失败的都用false表示
     */
    public static function getResult($operationResult)
    {
        $result = true;

        if (empty($operationResult)) {
            return false;
        }

        if (is_bool($operationResult)) {
            return $operationResult;
        }

        if (is_int($operationResult)) {
            if ($operationResult > 0) {
                return true;
            } else {
                return false;
            }
        }

        //确保某些情况下，字符串类型的数值也可以使用
        $intConverted = (int)$operationResult;
        if ($intConverted > 0) {
            return true;
        }

        //某些数据库表是以guid为主键的，因此加入guid的判断
        if (GuidHelper::determine($operationResult)) {
            return true;
        }

        if (is_string($operationResult)) {
            return false;
        }

        return $result;
    }

    /**
     * 构建计算机系统错误信息 json格式的字符串
     *
     * @param string $message
     *            错误内容
     * @param string $details
     *            错误详细信息
     * @param bool $isRollback
     *            是否回滚事务
     * @return string json格式的字符串
     */
    public static function buildSystemErrorResult($message, $details = '', $isRollback = false)
    {
        return self::buildErrorResult($message, $details, 1, $isRollback);
    }

    /**
     * 构建错误信息 json格式的字符串
     *
     * @param string $message
     *            错误内容
     * @param string $details
     *            错误详细信息
     * @param int $type
     *            错误类型，
     *            0表示计算机处理上的错误（比如无法插入相同的primary等）
     *            1表示业务逻辑上不满足条件的错误（比如余额不足无法完成支付等）
     * @param bool $isRollback
     *            是否回滚事务
     * @return string json格式的字符串
     */
    public static function buildErrorResult($message, $details = '', $type = 1, $isRollback = false)
    {
        if ($isRollback) {
            $transModel = new Model();
            $transModel->rollback();
        }

        $data['type'] = $type;
        $data['message'] = urlencode($message);
        $data['details'] = urlencode($details);

        return urldecode(json_encode($data));
    }

    /**
     * 构建业务逻辑错误信息 json格式的字符串
     *
     * @param string $message
     *            错误内容
     * @param string $details
     *            错误详细信息
     * @param bool $isRollback
     *            是否回滚事务
     * @return string json格式的字符串
     */
    public static function buildBizErrorResult($message, $details = '', $isRollback = false)
    {
        return self::buildErrorResult($message, $details, 0, $isRollback);
    }

    /**
     * 解析获取错误结果信息中的错误摘要信息
     *
     * @param string $errorResult
     *            错误结果（json格式的字符串）
     * @return mixed
     */
    public static function getErrorMessage($errorResult)
    {
        return self::getErrorElement($errorResult, 'message');
    }

    /**
     * 解析获取错误结果信息中的各元素内容
     *
     * @param string $errorResult
     *            错误结果（json格式的字符串）
     * @param string $elementName
     *            元素名称（支持type、message、details）
     * @return mixed
     */
    public static function getErrorElement($errorResult, $elementName)
    {
        $data = self::parseErrorResult($errorResult);
        return $data[$elementName];
    }

    /**
     * 解析获取错误结果信息为数组
     *
     * @param string $errorResult
     *            错误结果（json格式的字符串）
     * @return array
     */
    public static function parseErrorResult($errorResult)
    {
        return json_decode($errorResult, true);
    }
}

?>