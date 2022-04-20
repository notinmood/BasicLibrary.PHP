<?php
/**
 * @file   : dotEnvHelper.php
 * @time   : 0:00
 * @date   : 2022/1/4
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Environment\EnvHelper;

/**
 * .env 文件读取器
 * ════════════════════════
 * 参考
 * https://blog.csdn.net/weixin_38125045/article/details/109100324
 * 在其上优化了异常处理
 */
class dotEnvHelper
{
    static bool  $loaded      = false;
    static array $loadedArray = [];

    /**
     * 加载配置文件
     * @access public
     * @param string $filePath 配置文件路径
     * @return void
     */
    public static function loadFile(string $filePath)
    {
        self::$loaded = true;

        if (file_exists($filePath)) {
            self::$loadedArray = (new ConfigParserIni())->loadFileToArray($filePath);
        }
    }

    /**
     * 获取环境变量值
     * @access public
     * @param string $name    环境变量名（支持二级 . 号分割）
     * @param null   $default 默认值
     * @return array|bool|string|null
     */
    public static function get(string $name, $default = null)
    {
        if (!self::$loaded) {
            $root    = EnvHelper::getPhysicalRootPath();
            $envFile = $root . DIRECTORY_SEPARATOR . '.env';
            self::loadFile($envFile);
        }

        $result = $default;
        if (ObjectHelper::isExist(self::$loadedArray)) {
            $result = ArrayHelper::getNode(self::$loadedArray, $name);
        }

        if ($result) {
            return $result;
        } else {
            return $default;
        }
    }
}
