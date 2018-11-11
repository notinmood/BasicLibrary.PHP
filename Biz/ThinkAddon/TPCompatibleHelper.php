<?php
/**
 * Created by PhpStorm.
 * User: xiedali
 * Date: 2018/11/11
 * Time: 10:22
 */

namespace Hiland\Biz\ThinkAddon;


class TPCompatibleHelper
{
    /**读取配置节点的信息
     * @param $setingName 配置节点的名称
     */
    public static  function config($setingName){
        if (TPVersionHelper::getPrimaryVersion() > 3) {
            $result = config($setingName);
        } else {
            $result = C($setingName);
        }

        return $result;
    }

    /**
     * 读取/设置缓存
     * @param $name
     * @param $value
     * @param null $options
     * @param null $tags
     * @return mixed
     */
    public static function cache($name,$value='',$options=null,$tags=null){
        if (TPVersionHelper::getPrimaryVersion() > 3) {
            $result = cache($name,$value,$options,$tags);
        } else {
            $result = S($name,$value,$options,$tags);
        }

        return $result;
    }
}