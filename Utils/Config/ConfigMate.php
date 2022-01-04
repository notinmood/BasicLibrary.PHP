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
    private static $__configFileLoaded = [];
    private static $__currentConfigFileName = "";
    private static $__currentConfigParser = null;
    private static $__tempConfigFileName = "";
    private static $__longConfigFileName = "";

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

    // /**
    //  * 是否需要载入长效的config文件
    //  * @return bool
    //  */
    // private function isNeedLoadDefaultConfig()
    // {
    //     if (self::$__longConfigFileName) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }

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


    public function loadFile($fileName = "")
    {
        $rootPath = EnvHelper::getRootPhysicalPath();
        $defaultFileNames = ["config.php", "config.ini", "config.json"];

        $fileFullName = PathHelper::combine($rootPath, $fileName);
        if (!$fileName || !file_exists($fileFullName)) {
            foreach ($defaultFileNames as $defaultFileName) {
                $configFileFullName = PathHelper::combine($rootPath, $defaultFileName);
                if (file_exists($configFileFullName)) {
                    // $fileName = $defaultFileName;
                    $fileFullName = $configFileFullName;
                    break;
                }
            }
        }

        self::loadFileDetail($fileFullName);
        return $this;


        // self::$__currentConfigFileName = $fileName;
        // $thisFileLoaded = ArrayHelper::isContains(self::$__configFileLoaded, $fileName);
        // if (!$thisFileLoaded) {
        //     self::$__configFileLoaded[] = $fileName;
        //
        //     self::$__currentConfigParser = self::getParser($fileName);
        //     if (!self::getCurrentConfigContent($fileName)) {
        //         $result = null;
        //         $rootPath = EnvHelper::getRootPhysicalPath();
        //         $configFileFullName = PathHelper::combine($rootPath, $fileName);
        //         if (file_exists($configFileFullName)) {
        //             $result = self::$__currentConfigParser->loadFileToArray($configFileFullName);
        //         }
        //
        //         self::$__configContentArray[$fileName] = $result;
        //     }
        // }
        //
        // return $this;
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

    // private static function getCurrentConfigContent($fileFullName)
    // {
    //     if (ArrayHelper::isContainsKey(self::$__configContentArray, $fileFullName)) {
    //         return self::$__configContentArray[$fileFullName];
    //     } else {
    //         return null;
    //     }
    // }
}