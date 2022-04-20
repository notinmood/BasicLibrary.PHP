<?php

namespace Hiland\Utils\IO;

use Hiland\Utils\Data\StringHelper;

/**
 *
 */
class FileHelper
{
    /**
     * 获取文件所在的目录信息
     * @param string $fileName
     *            带路径的全文件名
     */
    public static function getDirName(string $fileName)
    {
        $path = pathinfo($fileName);
        return $path['dirname'];
    }

    /**
     * 获取文件的基本名称信息（不带路径的文件名称）
     * @param string $fileName 带路径的全文件名
     */
    public static function getBaseName(string $fileName)
    {
        $path = pathinfo($fileName);
        return $path['basename'];
    }

    /**
     * 获取文件的基本名称信息（不带路径不带扩张名的文件名称）
     * @param string $fileName 带路径的全文件名
     */
    public static function getBaseNameWithoutExtension(string $fileName)
    {
        $path = pathinfo($fileName);
        return $path['filename'];
    }

    /**
     * 获取文件扩展名称信息（扩展名不带点（“.”））
     * @param string $fileName 带路径的全文件名
     */
    public static function getExtensionName(string $fileName)
    {
        $path = pathinfo($fileName);
        return $path['extension'];
    }

    /**在页面通过form上传文件后，服务器获得的文件信息。
     * 页面提交的方式，基本如下
     * <form action="" method="post" enctype="multipart/form-data">
     * <label for="file">选择文件：</label>
     * <input type="file" name="file" id="file"><br>
     * <input type="submit" name="submit" value="提交">
     * </form>
     * @param string $submitControlName <input type="file" name="file" id="file">的name值
     * @return bool|mixed 失败返回false，成功返回文件在服务器上的信息，其中
     *                                  $result["fullName"] 带路径的全文件名称（临时路径和临时文件）
     *                                  $result["name"] 文件名称;
     *                                  $result["type"] 文件类型;
     *                                  $result["size"] 文件大小
     */
    public static function getUploadedFileInfo(string $submitControlName = 'file')
    {
        if ($_FILES[$submitControlName]["error"] > 0) {
            return false;
        } else {
            $result["fullName"] = $_FILES[$submitControlName]["tmp_name"];
            $result["name"]     = $_FILES[$submitControlName]["name"];
            $result["type"]     = $_FILES[$submitControlName]["type"];
            $result["size"]     = $_FILES[$submitControlName]["size"];

            return $result;
        }
    }

    /**
     * 获取文件编码名称
     * @param $fileName
     * @return bool|string
     */
    public static function getEncoding($fileName)
    {
        $content = file_get_contents($fileName);
        return StringHelper::getEncoding($content);
    }

    /**
     * 获取目标编码类型的文本
     * @param        $fileName
     * @param string $targetEncoding
     * @return false|string|string[]|null
     */
    public static function getEncodingContent($fileName, string $targetEncoding = 'UTF-8')
    {
        $content = file_get_contents($fileName);
        return StringHelper::getEncodingContent($content, $targetEncoding);
    }
}
