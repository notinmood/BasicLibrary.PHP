<?php


namespace Hiland\Utils\IO\File\FileUtilDriver;


use Hiland\Utils\Data\ReflectionHelper;

trait FileTrait
{
    /**按行的方式讀取文本文件
     * @param string $fileUrl 要读取的文件地址。
     * @param $afterReadLineCallback 每次讀取一行後，可以執行的回調函數
     * @return mixed 成功返回TRUE，失败返回FALSE
     */
    public function readLineOfText($fileUrl, $afterReadLineCallback)
    {
        try {
            $file = fopen($fileUrl, "r"); // 以只读的方式打开文件
            if (empty($file)) {
                return false;
            }

            //输出文本中所有的行，直到文件结束为止。
            while (!feof($file)) {
                $line = fgets($file); //fgets()函数从文件指针中读取一行
                ReflectionHelper::executeFunction($afterReadLineCallback, $line);
            }

            fclose($file);
        } catch (Exception $exception) {
            return false;
        }
        return true;
    }
}