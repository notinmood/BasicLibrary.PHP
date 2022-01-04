<?php
/**
 * @file   : ConfigMate.php
 * @time   : 16:40
 * @date   : 2021/8/11
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Utils\Config;

use Hiland\Utils\Data\ArrayHelper;
use Hiland\Utils\Data\ObjectHelper;
use Hiland\Utils\Data\StringHelper;
use Hiland\Utils\Environment\EnvHelper;
use Hiland\Utils\IO\FileHelper;
use Hiland\Utils\IO\PathHelper;

// use function Hiland\Utils\Environment\config;

/**
 * 配置文件交互的核心类(不直接向外暴露；外部请使用ConfigHelper访问配置信息)
 */
class ConfigMate
{
    private static $_instance = null;
    private static $__configContentArray = [];

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

    public function get($key, $default = null)
    {
        if (EnvHelper::isThinkPHP() && function_exists('config')) {
            return config($key);
        } else {
            if (ObjectHelper::isEmpty(self::$__configContentArray)) {
                self::loadFile();
            }

            foreach (self::$__configContentArray as $configFileFullName => $currentConfigContent) {
                $result = ArrayHelper::getNode($currentConfigContent, $key);
                if ($result != null) {
                    return $result;
                }
            }
            return $default;
        }
    }

    private function loadFileDetail($fileFullName)
    {
        $thisFileLoaded = ArrayHelper::isContainsKey(self::$__configContentArray, $fileFullName);
        if (!$thisFileLoaded) {
            $_parser = self::getParser($fileFullName);

            $fileContent = null;
            if (file_exists($fileFullName)) {
                $fileContent = $_parser->loadFileToArray($fileFullName);
            }

            self::$__configContentArray[$fileFullName] = $fileContent;
        }
    }

    /**
     * (因为有可能本方法位于链式操作，因此需要返回 this)
     * @param $fileName
     * @return $this
     */
    public function loadFile($fileName = "")
    {
        $rootPath = EnvHelper::getRootPhysicalPath();
        $defaultFileNames = ["config.php", "config.ini", "config.json"];

        $fileFullName = PathHelper::combine($rootPath, $fileName);
        if (!$fileName || !file_exists($fileFullName)) {
            foreach ($defaultFileNames as $defaultFileName) {
                $configFileFullName = PathHelper::combine($rootPath, $defaultFileName);
                if (file_exists($configFileFullName)) {
                    $fileFullName = $configFileFullName;
                    break;
                }
            }
        }

        self::loadFileDetail($fileFullName);
        return $this;
    }

    private static function getParser($fileName)
    {
        $extensionName = FileHelper::getExtensionName($fileName);
        $extensionName = StringHelper::upperStringFirstChar($extensionName);

        $targetParserType = "ConfigParser{$extensionName}";
        $targetParserClass = "Hiland\\Utils\\Config\\{$targetParserType}";
        $targetFileBaseName = "{$targetParserType}.php";
        $targetFileFullName = PathHelper::combine(__DIR__, $targetFileBaseName);
        if (file_exists($targetFileFullName)) {
            return new $targetParserClass();
        } else {
            return new ConfigParserArray();
        }
    }

}