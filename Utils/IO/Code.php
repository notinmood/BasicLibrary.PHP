<?php

namespace Hiland\Utils\IO;

/**
 * PHP代码处理逻辑
 *
 * @author 然
 * @version 20131226
 */
class Code
{
    /**
     * 去除代码中的注释，空格等对代码进行压缩
     *
     * @param string $content
     *            待压缩代码
     * @return string 压缩后的代码
     */
    public static function stripWhiteSpace($content)
    {
        $stripStr = '';
        // 分析php源码
        $tokens = token_get_all($content);
        $last_space = false;
        for ($i = 0, $j = count($tokens); $i < $j; $i++) {
            if (is_string($tokens [$i])) {
                $last_space = false;
                $stripStr .= $tokens [$i];
            } else {
                switch ($tokens [$i] [0]) {
                    // 过滤各种PHP注释
                    case T_COMMENT :
                    case T_DOC_COMMENT :
                        break;
                    // 过滤空格
                    case T_WHITESPACE :
                        if (!$last_space) {
                            $stripStr .= ' ';
                            $last_space = true;
                        }
                        break;
                    default :
                        $last_space = false;
                        $stripStr .= $tokens [$i] [1];
                }
            }
        }
        return $stripStr;
    }

    /**
     * 去除PHP代码的开始和结束标记
     *
     * @param string $content
     *            待处理代码
     * @return string 处理后的代码
     */
    public static function stripScriptTags($content)
    {
        $content = trim($content);
        $content = ltrim($content, '<?php');
        $content = rtrim($content, '?>');
        return $content;
    }
}