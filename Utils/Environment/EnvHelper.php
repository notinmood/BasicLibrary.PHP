<?php

namespace Hiland\Utils\Environment;


class EnvHelper
{
    /**获取系统信息
     * @return array
     */
    public static function getAllInfo()
    {
        return [
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            '主机名' => $_SERVER['SERVER_NAME'],
            '服务器CPU信息' => $_SERVER['PROCESSOR_IDENTIFIER'],
            '服务器系统目录' => $_SERVER['SystemRoot'],
            'WEB服务端口' => $_SERVER['SERVER_PORT'],
            '网站文档目录' => $_SERVER["DOCUMENT_ROOT"],
            '浏览器信息' => substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
            '通信协议' => $_SERVER['SERVER_PROTOCOL'],
            '请求方法' => $_SERVER['REQUEST_METHOD'],
            'PHP版本' => phpversion(),
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '用户的IP地址' => $_SERVER['REMOTE_ADDR'],
            '剩余空间' => round((disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            'MySQL数据库持续连接' => get_cfg_var("mysql.allow_persistent") ? "是 " : "否",
            '脚本运行占用最大内存' => get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : "无",
            '当前进程用户名' => Get_Current_User(),
        ];
    }

    /**
     * 是否运行在浏览器/服务器模式下
     * @return bool
     */
    public static function isCGI()
    {
        if (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否运行在命令行模式下
     * @return bool
     */
    public static function isCLI()
    {
        $sapi_type = php_sapi_name();
        if (isset($sapi_type) && substr($sapi_type, 0, 3) == 'cli') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否运行在windows系统内
     * @return bool
     */
    public static function isWIN()
    {
        $pos = strpos(PHP_OS, 'WIN');

        if ($pos >= 0) {
            return true;
        } else {
            return false;
        }
    }
}