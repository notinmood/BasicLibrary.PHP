<?php
/**
 * @file   : ServerHelper.php
 * @time   : 10:13
 * @date   : 2021/10/10
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Web;

use Hiland\Environment\EnvHelper;

/**
 * 提供访问服务器信息的统一接口
 * ────────────────────────
 * 本类型通过整合和暴露其他类型的功能而存在，自身不提供独立功能，
 */
class ServerHelper
{
    /**
     * 获取 Web 服务器名称（IIS 还是 apache 等）
     * @return mixed
     */
    public static function getWebServerSoftName(): mixed
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }

    /**
     * 判断当前服务器操作系统的名称
     * @return string (返回值为Linux或者Windows)
     */
    public static function getOSName(): string
    {
        return EnvHelper::getOS();
    }

    /**
     * 是否运行在windows系统内
     * @return bool
     */
    public static function isWIN(): bool
    {
        return EnvHelper::isWIN();
    }

    /**
     * 判断是否为本地服务器
     * @param $domainNameOrIP string 域名或ip地址
     * @return bool
     */
    public static function isLocalServer(string $domainNameOrIP): bool
    {
        return EnvHelper::isLocalServer($domainNameOrIP);
    }


    /**
     * 获取服务器域名 （例如app.rainytop.com）
     * @return string
     */
    public static function getHostName(): string
    {
        return WebHelper::getHostName();
    }

    /**
     * 获取服务器端的压缩方式
     * @param $url
     * @return false|mixed
     */
    public static function getCompressType($url): mixed
    {
        return HttpResponseHeader::get($url, "Content-Encoding");
    }

    /**
     * 获取 web 项目的根目录物理路径
     * ————————————————————
     *因为当前文件属于类库文件(假定名称为 a),
     *客户浏览器请求的页面(假定为 b)
     *当用 composer 加载的时候 a 的时候,
     *a、b 两个文件对应的物理文件,在根目录下是并列的存在的两个分支子目录.
     *因此可以通过以下逻辑获取到项目的根目录物理路径
     * @return string
     */
    public static function getPhysicalRoot(): string
    {
        return EnvHelper::getPhysicalRootPath();
    }

    /**
     * 获取应用程序的(网络)根路径
     * @return string
     */
    public static function getWebRoot(): string
    {
        return EnvHelper::getWebRootPath();
    }

    /**
     * 获取应用程序的名称
     * @return mixed|string
     */
    public static function getAppName(): mixed
    {
        return EnvHelper::getAppName();
    }
}
