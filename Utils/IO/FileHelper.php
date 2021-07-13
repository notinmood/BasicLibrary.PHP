<?php

namespace Hiland\Utils\IO;

use Hiland\Utils\Data\StringHelper;

class FileHelper
{
    /**
     * 获取文件所在的目录信息
     *
     * @param string $fileName
     *            带路径的全文件名
     */
    public static function getFileDirName($fileName)
    {
        $path = pathinfo($fileName);
        $dirname = $path['dirname'];
        return $dirname;
    }

    /**
     * 获取文件的基本名称信息（不带路径的文件名称）
     *
     * @param string $fileName
     *            带路径的全文件名
     */
    public static function getFileBaseName($fileName)
    {
        $path = pathinfo($fileName);
        $basename = $path['basename'];
        return $basename;
    }

    /**
     * 获取文件的基本名称信息（不带路径不带扩张名的文件名称）
     * @param string $fileName 带路径的全文件名
     */
    public static function getFileBaseNameWithoutExtension($fileName)
    {
        $path = pathinfo($fileName);
        $basename = $path['filename'];
        return $basename;
    }

    /**
     * 获取文件扩张名称信息（扩张名不带点（“.”））
     * @param string $fileName 带路径的全文件名
     */
    public static function getFileExtensionName($fileName)
    {
        $path = pathinfo($fileName);
        $extensionname = $path['extension'];
        return $extensionname;
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
     *
     * $result["fullName"] 带路径的全文件名称（临时路径和临时文件）
     * $result["name"] 文件名称;
     * $result["type"] 文件类型;
     * $result["size"] 文件大小
     */
    public static function getUploadedFileInfo($submitControlName = 'file')
    {
        if ($_FILES[$submitControlName]["error"] > 0) {
            return false;
        } else {
            $result["fullName"] = $_FILES[$submitControlName]["tmp_name"];
            $result["name"] = $_FILES[$submitControlName]["name"];
            $result["type"] = $_FILES[$submitControlName]["type"];
            $result["size"] = $_FILES[$submitControlName]["size"];

            return $result;
        }
    }

    /**
     * @param $fileName
     * @return bool|string
     */
    public static function getFileEncoding($fileName)
    {
        $content = file_get_contents($fileName);
        return StringHelper::getEncoding($content);
    }

    /**
     * @param $fileName
     * @param string $targetEncoding
     * @return false|string|string[]|null
     */
    public static function getEncodingContent($fileName, $targetEncoding = 'UTF-8')
    {
        $content = file_get_contents($fileName);
        return StringHelper::getEncodingContent($content, $targetEncoding);
    }

}