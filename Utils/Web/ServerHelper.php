<?php
/**
 * @file   : ServerHelper.php
 * @time   : 10:13
 * @date   : 2021/10/10
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Utils\Web;

use Hiland\Utils\Environment\EnvHelper;

class ServerHelper
{
    /**
     * 获取Web服务器名称（IIS还是apache等）
     * @return mixed
     */
    public static function getWebServerSoftName()
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }

    /**
     * 判断当前服务器操作系统的名称
     * @return string (返回值为Linux或者Windows)
     */
    public static function getOSName()
    {
        return EnvHelper::getOS();
    }

    /**
     * 是否运行在windows系统内
     * @return bool
     */
    public static function isWIN()
    {
        return EnvHelper::isWIN();
    }

    /**
     * 获取服务器域名 （例如app.rainytop.com）
     * @return string
     */
    public static function getHostName()
    {
        return isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    }

    /**
     * 获取服务器端的压缩方式
     * @param $url
     * @return false|mixed
     */
    public static function getCompressType($url)
    {
        return HttpResponseHeader::get($url, "Content-Encoding");
    }

    /**
     * 获取web项目的根目录物理路径
     * ————————————————————
     *因为当前文件属于类库文件(假定名称为a),
     *客户浏览器请求的页面(假定为b)
     *当用composer加载的时候a的时候,
     *a、b两个文件对应的物理文件,在根目录下是并列的存在的两个分支子目录.
     *因此可以通过以下逻辑获取到项目的根目录物理路径
     * @return string
     */
    public static function getRootPhysicalPath()
    {
        return EnvHelper::getRootPhysicalPath();
    }

    /**
     * 判断是否为本地服务器
     * @param $domainNameOrIP string 域名或ip地址
     * @return bool
     */
    public static function isLocalServer($domainNameOrIP)
    {
        return EnvHelper::isLocalServer($domainNameOrIP);
    }
}