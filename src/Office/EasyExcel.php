<?php
/**
 * @file   : EasyExcel.php
 * @time   : 10:16
 * @date   : 2025/4/3
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Hiland\Office;

class EasyExcel
{
    /**
     * @desc 数据导出到excel(csv文件)的简易实现
     * @param string $filename 导出的csv文件名称，默认使用当前时间作为文件名
     * @param array $titleArray 所有列名称
     * @param array $dataArray 所有列数据
     */
    public static function export(string $filename = "", array $titleArray = [], array $dataArray = []): void
    {
        if (empty($filename)) {
            $filename = date("Y-m-d H:i:s") . '.csv';
        }

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=" . $filename);
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));//转码 防止乱码(比如微信昵称(乱七八糟的))
        fputcsv($fp, $titleArray);
        $index = 0;
        foreach ($dataArray as $item) {
            if ($index === 100) {
                $index = 0;
                ob_flush();
                flush();
            }
            $index++;
            fputcsv($fp, $item);
        }
        ob_flush();
        flush();
        ob_end_clean();
    }
}