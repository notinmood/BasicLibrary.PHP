<?php
/*
 * Copyright (c) General. 2022-2026. All rights reserved.
 * @file     : ItemListGenerator.php
 * @time     : 20:07:27
 * @date     : 2026/01/20
 * @mail     : 9727005@qq.com
 * @creator  : ShanDong Xiedali
 * @objective: Less is more. Simple is best!
 */

namespace Hiland\Tools;
require "../vendor/autoload.php";

use Hiland\Data\StringHelper;

class ItemListGenerator
{
    // 主函数
    public static function generate(string $targetDir, string|array $fileExtensions): void
    {
        try {
            // 确保目标目录存在
            if (!file_exists($targetDir)) {
                echo "❌目录 " . $targetDir . " 不存在，请检查路径！\n";
                exit();
            }

            echo "》》》正在处理目录：" . $targetDir . "\n";
            $items = static::processDirectory($targetDir, $fileExtensions);
            static::generateMarkdownFile($targetDir, $items);
            echo "✅✅✅处理完成。\n";
        } catch (Exception $error) {
            echo "处理过程中发生错误：" . $error->getMessage() . "\n";
        }
    }

    // 从 export default 块中提取 name 或 title 和 description 或 describe
    private static function getExportInfo(string $filePath, string $fileContent): ?ExportInfo
    {
        //1-> 匹配标题 name 或 title
        preg_match('/\/\/\s\|:TITLE:{7}\|\s*(.*)/i', $fileContent, $match);
        if (empty($match) || count($match) < 2) {
            return null;
        }

        $nameOrTitle = $match[1];
        if (StringHelper::isEndWith($nameOrTitle, "\r", "\n")) {
            $nameOrTitle = StringHelper::removeTail($nameOrTitle, 1);
        }

        // 2-> 匹配 description 或 describe
        preg_match('/\/\/\s\|:DESCRIPTION:\|\s*(.*)/i', $fileContent, $descriptionMatch);
        $descriptionOrDescribe = $descriptionMatch ? $descriptionMatch[1] : "(未提供描述)";
        if (StringHelper::isEndWith($descriptionOrDescribe, "\r", "\n")) {
            $descriptionOrDescribe = StringHelper::removeTail($descriptionOrDescribe, 1);
        }

        return new ExportInfo($filePath, $nameOrTitle, $descriptionOrDescribe);
    }

    // 遍历目录并处理文件，生成 00.ItemList.md
    private static function processDirectory($dirPath, $fileExtensions): array
    {
        if (empty($fileExtensions)) {
            $fileExtensions = ".php";
        }

        if (is_string($fileExtensions)) {
            $fileExtensions = explode(",", $fileExtensions);
        }

        $items = [];

        $dirItems = scandir($dirPath);
        foreach ($dirItems as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $dirPath . DIRECTORY_SEPARATOR . $item;

            if (is_dir($itemPath)) {
                // 递归处理子目录
                $items = array_merge($items, static::processDirectory($itemPath, $fileExtensions));
            } elseif (is_file($itemPath) && StringHelper::isEndWith($item, ...$fileExtensions)) {
                $content    = file_get_contents($itemPath);
                $exportInfo = static::getExportInfo($itemPath, $content);
                if ($exportInfo) {
                    $items[] = $exportInfo;
                }
            }
        }

        return $items;
    }

    /**
     * @param array $items
     * @param string $dirPath
     * @return void
     */
    private static function generateMarkdownFile(string $dirPath, array $items): void
    {
        // 生成 Markdown 列表
        if ($items) {
            $markdownContent = array_map(static function ($item) {
                return sprintf('- [%s](%s) - %s', $item->nameOrTitle, $item->filePath, $item->descriptionOrDescribe);
            }, $items);

            $markdownContent = implode("\n", $markdownContent);

            // 写入到 00.ItemList.md
            $outFilePath     = $dirPath . DIRECTORY_SEPARATOR . '00.ITEMLIST.md';
            $markdownContent = "# 目录\n\n" . $markdownContent;
            file_put_contents($outFilePath, $markdownContent);
            echo "已生成 " . $outFilePath . "\n";
        }
    }


}

// 定义一个类来存储 name 或 title，以及 description 或 describe
class ExportInfo
{
    public string $filePath;
    public string $nameOrTitle;
    public string $descriptionOrDescribe;

    public function __construct(string $filePath, string $nameOrTitle, string $descriptionOrDescribe = "(未提供描述)")
    {
        $this->filePath              = $filePath;
        $this->nameOrTitle           = $nameOrTitle;
        $this->descriptionOrDescribe = $descriptionOrDescribe;
    }
}

