<?php

namespace Hiland\IO;

use Hiland\Data\StringHelper;

/**
 * 文件操作工具类
 */
class FileHelper
{
    /**
     * 获取文件所在的目录信息
     * @param string $fileFullName 带路径的全文件名
     * @return string 返回文件所在的目录信息
     */
    public static function getDirName(string $fileFullName): string
    {
        $path = pathinfo($fileFullName);
        return $path['dirname'];
    }

    /**
     * 获取文件的基本名称信息（不带路径的文件名称）
     * @param string $fileFullName 带路径的全文件名
     * @return string 返回文件的基本名称信息（不带路径的文件名称）
     */
    public static function getBaseName(string $fileFullName): string
    {
        $path = pathinfo($fileFullName);
        return $path['basename'];
    }

    /**
     * 获取文件的基本名称信息（不带路径不带扩张名的文件名称）
     * @param string $fileFullName 带路径的全文件名
     */
    public static function getBaseNameWithoutExtension(string $fileFullName)
    {
        $path = pathinfo($fileFullName);
        return $path['filename'];
    }

    /**
     * 获取文件扩展名称信息（扩展名不带点（“.”））
     * @param string $fileFullName 带路径的全文件名
     */
    public static function getExtensionName(string $fileFullName)
    {
        $path = pathinfo($fileFullName);
        return $path['extension'];
    }

    /**
     * 在页面通过form上传文件后，服务器获得的文件信息。
     * 页面提交的方式，基本如下
     * <form action="" method="post" enctype="multipart/form-data">
     * <label for="file">选择文件：</label>
     * <input type="file" name="file" id="file"><br>
     * <input type="submit" name="submit" value="提交">
     * </form>
     * @param string $submitControlName <input type="file" name="file" id="file">的name值
     * @return array|bool 失败返回false，成功返回文件在服务器上的信息，其中
     *                                  $result["fullName"] 带路径的全文件名称（临时路径和临时文件）
     *                                  $result["name"] 文件名称;
     *                                  $result["type"] 文件类型;
     *                                  $result["size"] 文件大小
     */
    public static function getUploadedFileInfo(string $submitControlName = 'file'): array|bool
    {
        if ($_FILES[$submitControlName]["error"] > 0) {
            return false;
        }

        $result["fullName"] = $_FILES[$submitControlName]["tmp_name"];
        $result["name"]     = $_FILES[$submitControlName]["name"];
        $result["type"]     = $_FILES[$submitControlName]["type"];
        $result["size"]     = $_FILES[$submitControlName]["size"];

        return $result;
    }

    /**
     * 获取文件编码名称
     * @param $fileFullName
     * @return bool|string
     */
    public static function getEncoding($fileFullName): bool|string
    {
        $content = file_get_contents($fileFullName);
        return StringHelper::getEncoding($content);
    }

    /**
     * 获取文件内容（getEncodingContent的别名）
     * @param string $fileFullName
     * @param string $targetEncoding 目标编码，默认原文件编码不改变
     * @return array|bool|string|null
     */
    public function getContent(string $fileFullName, string $targetEncoding = ''): array|bool|string|null
    {
        return self::getEncodingContent($fileFullName, $targetEncoding);
    }

    /**
     * 获取编码类型的目标文本
     * @param        $fileFullName
     * @param string $targetEncoding
     * @return false|string|string[]|null
     */
    public static function getEncodingContent($fileFullName, string $targetEncoding = 'UTF-8'): array|bool|string|null
    {
        $content = file_get_contents($fileFullName);

        if (empty($targetEncoding)) {
            return $content;
        }

        return StringHelper::getEncodingContent($content, $targetEncoding);
    }
}
