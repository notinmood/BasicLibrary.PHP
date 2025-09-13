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

        // +--------------------------------------------------------------------------
        // |::说明·| 给文件加个 UTF-8 签名，让下游软件认出这是 UTF-8。
        // +--------------------------------------------------------------------------
        // |生成的新文件以 BOM 头开始，Windows 下用记事本等程序打开时会被识别为 UTF-8；
        // |无 BOM 时某些软件（Excel、老旧编辑器）可能把中文当成 ANSI 导致乱码。
        // |三个字节 0xEF 0xBB 0xBF 就是 UTF-8 的 BOM。
        // +--------------------------------------------------------------------------
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

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