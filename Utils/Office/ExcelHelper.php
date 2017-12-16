<?php

namespace Hiland\Utils\Office;
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2017/6/10
 * Time: 9:51
 */

require_once 'autoload.php';

class ExcelHelper
{
    public static function getSheetContent($excelFile, $sheetIndex = 0, $titleRowNumber = 1)
    {
        self::setErrorDispose();

        if ($titleRowNumber < 0) {
            $titleRowNumber = 0;
        }

        $objPHPExcel = \PHPExcel_IOFactory::load($excelFile);

        $sheet = $objPHPExcel->getSheet($sheetIndex);

        //获取行数与列数,注意列数需要转换
        $highestRowNum = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnNum = \PHPExcel_Cell::columnIndexFromString($highestColumn);

        //取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
        $filed = array();

        for ($i = 0; $i < $highestColumnNum; $i++) {
            if ($titleRowNumber == 0) {
                $cellVal = $i;
            } else {
                $cellName = \PHPExcel_Cell::stringFromColumnIndex($i) . $titleRowNumber;
                $cellVal = $sheet->getCell($cellName)->getValue();//取得列内容
            }

            $filed [] = $cellVal;
        }


        //开始取出数据并存入数组
        $data = array();
        for ($i = $titleRowNumber + 1;
             $i <= $highestRowNum;
             $i++) {//ignore row title
            $row = array();
            for ($j = 0;
                 $j < $highestColumnNum;
                 $j++) {
                $cellName = \PHPExcel_Cell::stringFromColumnIndex($j) . $i;
                $cellVal = $sheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }

            $data [] = $row;
        }

        return $data;
    }

    public static function save($sheetContentArray, $exportFileFullName, $sheetIndex = 0, $sheetTitle = 'Sheet1', $useExcelNewFormat = true)
    {
        self::setErrorDispose();
        $phpExcel = self::setExortExcelBasicInfo($sheetContentArray, $sheetIndex, $sheetTitle);

        if ($useExcelNewFormat) {
            // 保存Excel 2007格式文件，保存路径为当前路径，名字为export.xlsx
            $objWriter = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
            $objWriter->save($exportFileFullName);
        } else {// 保存Excel 95格式文件，，保存路径为当前路径，
            $objWriter = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
            $objWriter->save($exportFileFullName);
        }
    }

    private static function setExortExcelBasicInfo($sheetContentArray, $sheetIndex = 0, $sheetTitle = 'Sheet1')
    {
        $phpExcel = new \PHPExcel();

        //设置基本信息
//        $excelWriter->getProperties()->setCreator("jecken")
//            ->setLastModifiedBy("jecken")
//            ->setTitle("上海**人力资源服务有限公司")
//            ->setSubject("简历列表")
//            ->setDescription("")
//            ->setKeywords("简历列表")
//            ->setCategory("");

        $phpExcel->setActiveSheetIndex($sheetIndex);
        $phpExcel->getActiveSheet()->setTitle($sheetTitle);

        $phpExcel->getActiveSheet()->fromArray($sheetContentArray);

        return $phpExcel;
    }

    public static function download($sheetContentArray, $fileName = '导出文件', $sheetIndex = 0, $sheetTitle = 'Sheet1')
    {
        self::setErrorDispose();
        $phpExcel = self::setExortExcelBasicInfo($sheetContentArray, $sheetIndex, $sheetTitle);


        //保存为2003格式
        $objWriter = new \PHPExcel_Writer_Excel5 ($phpExcel);

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");

        //多浏览器下兼容中文标题
        $encoded_filename = urlencode($fileName);
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
        }

        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

    private static function setErrorDispose()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Shanghai');
    }
}