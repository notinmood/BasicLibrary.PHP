<?php
namespace Hiland\Utils\IO;

class FileHelper
{

    /**
     * 获取文件所在的目录信息
     *
     * @param string $filename
     *            带路径的全文件名
     */
    public static function getFileDirName($filename)
    {
        $path = pathinfo($filename);
        $dirname = $path['dirname'];
        return $dirname;
    }

    /**
     * 获取文件的基本名称信息（不带路径的文件名称）
     *
     * @param string $filename
     *            带路径的全文件名
     */
    public static function getFileBaseName($filename)
    {
        $path = pathinfo($filename);
        $basename = $path['basename'];
        return $basename;
    }

    /**
     * 获取文件的基本名称信息（不带路径不带扩张名的文件名称）
     *
     * @param string $filename
     *            带路径的全文件名
     */
    public static function getFileBaseNameWithoutExtension($filename)
    {
        $path = pathinfo($filename);
        $basename = $path['filename'];
        return $basename;
    }

    /**
     * 获取文件扩张名称信息（扩张名不带点（“.”））
     *
     * @param string $filename
     *            带路径的全文件名
     */
    public static function getFileExtensionName($filename)
    {
        $path = pathinfo($filename);
        $extensionname = $path['extension'];
        return $extensionname;
    }
}

?>