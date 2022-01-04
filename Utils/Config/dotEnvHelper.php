<?php
/**
 * @file   : dotEnvHelper.php
 * @time   : 0:00
 * @date   : 2022/1/4
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Environment\EnvHelper;

/**
 * .env文件读取器
 * ════════════════════════
 * 参考
 * https://blog.csdn.net/weixin_38125045/article/details/109100324
 * 在其上优化了异常处理
 */
class dotEnvHelper
{
    static $loaded = false;
    const ENV_PREFIX = 'PHP_';
    static $loadedArray = [];

    /**
     * 加载配置文件
     * @access public
     * @param string $filePath 配置文件路径
     * @return void
     */
    public static function loadFile($filePath)
    {
        self::$loaded = true;

        if (file_exists($filePath)) {
            self::$loadedArray = (new ConfigParserIni())->loadFileToArray($filePath);

            // /**
            //  * 往系统环境放入一份变量
            //  */
            // foreach (self::$loadedArray as $key => $val) {
            //     $prefix = static::ENV_PREFIX . strtoupper($key);
            //     if (is_array($val)) {
            //         foreach ($val as $k => $v) {
            //             $item = $prefix . '_' . strtoupper($k);
            //             putenv("$item=$v");
            //         }
            //     } else {
            //         putenv("$prefix=$val");
            //     }
            // }
        }
    }

    /**
     * 获取环境变量值
     * @access public
     * @param string $name    环境变量名（支持二级 . 号分割）
     * @param string $default 默认值
     * @return array|bool|string|null
     */
    public static function get($name, $default = null)
    {
        if (!self::$loaded) {
            $root = EnvHelper::getRootPhysicalPath();
            $envFile = $root . DIRECTORY_SEPARATOR . '.env';
            self::loadFile($envFile);
        }

        $result =$default;
        if(ObjectHelper::isExist(self::$loadedArray)){
            $result= ArrayHelper::getNode(self::$loadedArray,$name);
        }

        if($result){
            return $result;
        }else{
            return $default;
        }

        // $result = getenv(static::ENV_PREFIX . strtoupper(str_replace('.', '_', $name)));
        //
        // if (false !== $result) {
        //     if ('false' === $result) {
        //         $result = false;
        //     } elseif ('true' === $result) {
        //         $result = true;
        //     }
        //     return $result;
        // }
        // return $result;
    }
}