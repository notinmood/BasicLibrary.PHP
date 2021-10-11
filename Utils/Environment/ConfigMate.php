<?php
/**
 * @file   : ConfigMate.php
 * @time   : 16:40
 * @date   : 2021/8/11
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Environment;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\StringHelper;
use Hiland\Utils\IO\FileHelper;
use Hiland\Utils\IO\PathHelper;

/**
 * 配置文件交互的核心类
 */
class ConfigMate
{
    private static $_instance = null;

    private function __construct()
    {
        //do nothing;
    }

    public static function Instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private static $__configContentArray = [];
    private static $__currentConfigFileName = "";
    private static $__currentConfigParser = null;

    public function get($key, $default = null)
    {
        $result = null;

        if (EnvHelper::isThinkPHP() && function_exists('config')) {
            $result = config($key);
        } else {
            //确保至少加载缺省的config.php文件
            if (self::isNeedLoadDefaultConfig()) {
                $this->loadFile();
            }

            $nameNodes = explode(".", $key);

            $configContent = self:: getCurrentConfigContent();
            if ($configContent) {
                if ($nameNodes[0]) {
                    $result = $configContent[$nameNodes[0]];
                }

                for ($i = 1; $i < count($nameNodes); $i++) {
                    if (isset($result[$nameNodes[$i]])) {
                        $result = $result[$nameNodes[$i]];
                    } else {
                        break;
                    }
                }
            }
        }

        if (ObjectHelper::isEmpty($result)) {
            $result = $default;
        }

        return $result;
    }


    private static function getCurrentConfigContent()
    {
        $fileName = self::$__currentConfigFileName;
        if (ArrayHelper::containsKey(self::$__configContentArray, $fileName)) {
            return self::$__configContentArray[$fileName];
        } else {
            return null;
        }
    }

    /**
     * 是否需要自动载入缺省的config文件
     * @return bool
     */
    private function isNeedLoadDefaultConfig()
    {
        if (ArrayHelper::getLength(self::$__configContentArray) == 0) {
            return true;
        } else {
            return false;
        }
    }

    private static function getParser($fileName)
    {
        $extensionName = FileHelper::getExtensionName($fileName);
        $extensionName = StringHelper::upperStringFirstChar($extensionName);

        $targetParserType = "ConfigParser{$extensionName}";
        $targetParserClass = "Hiland\\Utils\\Environment\\{$targetParserType}";
        $targetFileBaseName = "{$targetParserType}.php";
        $targetFileFullName = PathHelper::combine(__DIR__, $targetFileBaseName);
        if (file_exists($targetFileFullName)) {
            return new $targetParserClass();
        } else {
            return new ConfigParserArray();
        }
    }

    public function loadFile($fileName = "config.php")
    {
        self::$__currentConfigFileName = $fileName;
        self::$__currentConfigParser = self::getParser($fileName);
        if (!self::getCurrentConfigContent($fileName)) {
            $result = null;
            $rootPath = EnvHelper::getRootPhysicalPath();
            $configFileFullName = PathHelper::combine($rootPath, $fileName);
            if (file_exists($configFileFullName)) {
                $result = self::$__currentConfigParser->loadFileToArray($configFileFullName);
            }

            self::$__configContentArray[$fileName] = $result;
        }

        return $this;
    }
}