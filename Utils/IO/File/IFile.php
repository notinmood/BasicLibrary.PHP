<?php


namespace Hiland\Utils\IO\File;


interface IFile
{
    /*
     * 删除指定文件
     * @param string $aimUrl 要删除的文件地址。
     * @return boolen
    */
    public function unlinkFile($aimUrl);

    /*
     * 移动指定文件
     * @param string $fileUrl 要移动的文件地址。
     * @param string $aimUrl 移动后的新地址。
     * @param boolen $overWrite 是否覆盖已存在的文件。
     * @return boolen
    */
    public function moveFile($fileUrl, $aimUrl, $overWrite = true);

    /*
     * 获取目录下子目录及文件列表
     * @param string $dirUrl 要扫描的目录地址。
     * @return array
    */
    public function getList($dirUrl);


    /*
     * 清空目录
     * @param string $dirUrl 要清空的目录地址。
     * @return boolen
    */
    public function clearDir($dirUrl);

    /*
     * 删除目录
     * @param string $dirUrl 要删除的目录地址。
     * @return boolen
     */
    public function unlinkDir($dirUrl);

    /*
     * 读取文件
     * @param string $fileUrl 要读取的文件地址。
     * @return 成功返回文件内容，失败返回FALSE
    */
    public function readFile($fileUrl);

    /**按行的方式讀取文本文件
     * @param string $fileUrl 要读取的文件地址。
     * @param $afterReadLineCallback 每次讀取一行後，可以執行的回調函數
     * @param null $callbackParams 回调函数的参数
     * @return mixed 成功返回TRUE，失败返回FALSE
     */
    public function readLineOfText($fileUrl,$afterReadLineCallback, $callbackParams = null);

    /*
     * 写入文件
     * @param string $fileUrl 要写入的文件地址。
     * @param string $content 写入内容
     * @return 成功返回文件地址，失败返回FALSE
    */
    public function writeFile($fileUrl, $content);

    /*
     * 将本地地址转换为当前环境地址
     * @param string $url 要转换的地址。
     * @return string
    */
    public function encodeUrl($url);

    /*
     * 将当前环境地址转换为本地地址
     * @param string $url 要转换的地址。
     * @return string
    */
    public function decodeUrl($url);

    /*
     * 判断文件是否存在（不能用于判断目录）
     * @param string $fileUrl 要判断的文件地址。
     * @return boolen
    */
    public function fileExists($fileUrl);
}