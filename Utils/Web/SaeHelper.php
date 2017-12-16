<?php
namespace Hiland\Utils\Web;

use Hiland\Utils\IO\FileHelper;

class SaeHelper
{
    /**
     * 向saestorage保存图片
     *
     * @param string $imagefilename
     *            原始图片的文件带路径的全文件名
     * @param string $savedimagebasename
     *            保存在saestorage中图片的名称（可以带目录信息，
     *            此目录将会在saestorage的domain下建立子目录）
     *            （为null时，使用原图片的基本名）
     * @param string $storagedomainname
     *            saestorage的domain名称
     * @return string 保存在saestorage中图片文件的url
     */
    public static function saveImage($imagefilename, $savedimagebasename = null, $storagedomainname = 'public')
    {
        $fileextionname = strtolower(FileHelper::getFileExtensionName($imagefilename));
        $filebasename = FileHelper::getFileBaseName($imagefilename);

        if (empty($savedimagebasename)) {
            $savedimagebasename = $filebasename;
        }

        /* $storage = new \SaeStorage();
        ob_start(); */

        switch ($fileextionname) {
            case 'png':
                $image = imagecreatefrompng($imagefilename);
                //imagepng($image);
                break;
            case 'gif':
                $image = imagecreatefromgif($imagefilename);
                //imagegif($image);
                break;
            case 'bmp':
                $image = imagecreatefromwbmp($imagefilename);
                //imagexbm($image);
                break;
            default:
                $image = imagecreatefromjpeg($imagefilename);
                //imagejpeg($image);
                break;
        }

        $url = self::saveImageResource($image, $savedimagebasename, $storagedomainname);
        return $url;
    }

    /**
     * 向saestorage保存图片
     *
     * @param resource $image
     *            原始图片的资源信息
     * @param string $savedimagebasename
     *            保存在saestorage中图片的名称（可以带目录信息，
     *            此目录将会在saestorage的domain下建立子目录）
     *            （为null时，使用原图片的基本名）
     * @param string $storagedomainname
     *            saestorage的domain名称
     * @return string 保存在saestorage中图片文件的url
     */
    public static function saveImageResource($image, $savedimagebasename, $storagedomainname = 'public')
    {
        $fileextionname = strtolower(FileHelper::getFileExtensionName($savedimagebasename));

        $storage = new \SaeStorage();
        ob_start();

        switch ($fileextionname) {
            case 'png':
                imagepng($image);
                break;
            case 'gif':
                imagegif($image);
                break;
            case 'bmp':
                imagexbm($image,null);
                break;
            default:
                imagejpeg($image);
                break;
        }

        $imagecontent = ob_get_contents();
        $storage->write($storagedomainname, $savedimagebasename, $imagecontent);
        ob_end_clean();

        $url = $storage->getUrl($storagedomainname, $savedimagebasename);
        return $url;
    }

    /**
     * 保存到sae中一个临时文件并获得文件的物理绝对路径(仅在当前请求期间有效，跨请求本数据无效)
     * @param resource $image
     * @param string $savingImageBaseName 要保存的图片的基本名称（仅文件名和扩展名）
     * @return string
     */
    public static function saveTempImageResource($image, $savingImageBaseName)
    {
        $fileextionname = strtolower(FileHelper::getFileExtensionName($savingImageBaseName));

        $filefullname = SAE_TMP_PATH . $savingImageBaseName;

        switch ($fileextionname) {
            case 'png':
                imagepng($image, $filefullname);
                break;
            case 'gif':
                imagegif($image, $filefullname);
                break;
            case 'bmp':
                imagexbm($image, $filefullname);
                break;
            default:
                imagejpeg($image, $filefullname);
                break;
        }

        return $filefullname;
    }

    /**
     * 获取SAE服务器上mysql的连接信息
     * @return array
     */
    public static function getMysqlConnectionInfo()
    {
        $result['host'] = SAE_MYSQL_HOST_M;
        $result['port'] = SAE_MYSQL_PORT;
        $result['user'] = SAE_MYSQL_USER;
        $result['password'] = SAE_MYSQL_PASS;
        $result['database'] = SAE_MYSQL_DB;

        return $result;
    }
}

?>