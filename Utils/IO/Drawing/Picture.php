<?php

namespace Hiland\Utils\IO\Drawing;

/*
 * 图片处理函数功能：缩放、剪切、相框、水印、锐化、旋转、翻转、透明度、反色
 *  处理并保存历史记录的思路：当有图片有改动时自动生成一张新图片，
 *  命名方式可以考虑在原图片的基础上加上步骤，例如：图片名称+__第几步
 */
class Picture
{
    var $PICTURE_URL; // 要处理的图片
    var $DEST_URL = "temp__01.jpg"; // 生成目标图片位置
    var $PICTURE_CREATE; // 要创建的图片
    var $TURE_COLOR; // 新建一个真彩图象
    var $PICTURE_WIDTH; // 原图片宽度
    var $PICTURE_HEIGHT; // 原图片高度

    /*
     * 水印的类型，默认的为水印文字
     */
    var $MARK_TYPE = 1;
    var $WORD; // 经过UTF-8后的文字
    var $WORD_X; // 文字横坐标
    var $WORD_Y; // 文字纵坐标
    var $FONT_TYPE; // 字体类型
    var $FONT_SIZE = "12"; // 字体大小
    var $FONT_WORD; // 文字
    var $ANGLE = 0; // 文字的角度，默认为0
    var $FONT_COLOR = "#000000"; // 文字颜色
    var $FONT_PATH = "font/simkai.ttf"; // 字体库，默认为宋体

    var $FORCE_URL; // 水印图片
    var $FORCE_X = 0; // 水印横坐标
    var $FORCE_Y = 0; // 水印纵坐标
    var $FORCE_START_X = 0; // 切起水印的图片横坐标
    var $FORCE_START_Y = 0; // 切起水印的图片纵坐标
    var $PICTURE_TYPE; // 图片类型
    var $PICTURE_MIME; // 输出的头部

    /*
     * 缩放比例为1的话就按缩放高度和宽度缩放
     */
    var $ZOOM = 1; // 缩放类型
    var $ZOOM_MULTIPLE; // 缩放比例
    var $ZOOM_WIDTH; // 缩放宽度
    var $ZOOM_HEIGHT; // 缩放高度

    /*
     * 裁切，按比例和固定长度、宽度
     */
    var $CUT_TYPE = 1; // 裁切类型
    var $CUT_X = 0; // 裁切的横坐标
    var $CUT_Y = 0; // 裁切的纵坐标
    var $CUT_; // 裁切的宽度
    var $CUT_HEIGHT = 100; // 裁切的高度

    /*
     * 锐化
     */
    var $SHARP = "7.0"; // 锐化程度

    /*
     * 透明度处理
     */
    var $ALPHA = '100'; // 透明度在0-127之间
    var $ALPHA_X = "90";
    var $ALPHA_Y = "50";

    /*
     * 任意角度旋转
     */
    var $CIRCUMROTATE = "90.0"; // 注意，必须为浮点数

    /*
     * 出错信息
     */
    var $ERROR = array(
        'unalviable' => '没有找到相关图片!'
    );

    /*
     * 构造函数：函数初始化
     */
    function __construct($PICTURE_URL)
    {
        $this->get_info($PICTURE_URL);
    }

    function get_info($PICTURE_URL)
    {
        /*
         * 处理原图片的信息,先检测图片是否存在,不存在则给出相应的信息
         */
        @$SIZE = getimagesize($PICTURE_URL);
        if (!$SIZE) {
            exit ($this->ERROR ['unalviable']);
        }

        // 得到原图片的信息类型、宽度、高度
        $this->PICTURE_MIME = $SIZE ['mime'];
        $this->PICTURE_;
        $this->PICTURE_HEIGHT = $SIZE [1];

        // 创建图片
        switch ($SIZE [2]) {
            case 1 :
                $this->PICTURE_CREATE = imagecreatefromgif($PICTURE_URL);
                $this->PICTURE_TYPE = "imagejpeg";
                $this->PICTURE_EXT = "jpg";
                break;
            case 2 :
                $this->PICTURE_CREATE = imagecreatefromjpeg($PICTURE_URL);
                $this->PICTURE_TYPE = "imagegif";
                $this->PICTURE_EXT = "gif";
                break;
            case 3 :
                $this->PICTURE_CREATE = imagecreatefrompng($PICTURE_URL);
                $this->PICTURE_TYPE = "imagepng";
                $this->PICTURE_EXT = "png";
                break;
        }

        /*
         * 文字颜色转换16进制转换成10进制
         */
        preg_match_all("/([0-f]){2,2}/i", $this->FONT_COLOR, $MATCHES);
        if (count($MATCHES) == 3) {
            $this->RED = hexdec($MATCHES [0] [0]);
            $this->GREEN = hexdec($MATCHES [0] [1]);
            $this->BLUE = hexdec($MATCHES [0] [2]);
        }
    }

    // nd of __construct

    /*
     * 将16进制的颜色转换成10进制的（R，G，B）
     */
    function hex2dec()
    {
        preg_match_all("/([0-f]){2,2}/i", $this->FONT_COLOR, $MATCHES);
        if (count($MATCHES) == 3) {
            $this->RED = hexdec($MATCHES [0] [0]);
            $this->GREEN = hexdec($MATCHES [0] [1]);
            $this->BLUE = hexdec($MATCHES [0] [2]);
        }
    }

    // 缩放类型
    function zoom_type($ZOOM_TYPE)
    {
        $this->ZOOM = $ZOOM_TYPE;
    }

    // 对图片进行缩放,如果不指定高度和宽度就进行缩放
    function zoom()
    {
        // 缩放的大小
        if ($this->ZOOM == 0) {
            $this->ZOOM_;
            gt;
            PICTURE_WIDTH * $this->ZOOM_MULTIPLE;
            $this->ZOOM_HEIGHT = $this->PICTURE_HEIGHT * $this->ZOOM_MULTIPLE;
        }
        // 新建一个真彩图象
        $this->TRUE_COLOR = imagecreatetruecolor($this->ZOOM_WIDTH, $this->ZOOM_HEIGHT);
        $WHITE = imagecolorallocate($this->TRUE_COLOR, 255, 255, 255);
        imagefilledrectangle($this->TRUE_COLOR, 0, 0, $this->ZOOM_WIDTH, $this->ZOOM_HEIGHT, $WHITE);
        imagecopyresized($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->ZOOM_WIDTH, $this->ZOOM_HEIGHT, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
    }

    // nd of zoom
    // 裁切图片,按坐标或自动
    function cut()
    {
        $this->TRUE_COLOR = imagecreatetruecolor($this->CUT_WIDTH, $this->CUT_WIDTH);
        imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, $this->CUT_X, $this->CUT_Y, $this->CUT_WIDTH, $this->CUT_HEIGHT);
    }

    // nd of cut
    /*
     * 在图片上放文字或图片 水印文字
     */
    function _mark_text()
    {
        $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        $this->WORD = mb_convert_encoding($this->FONT_WORD, 'utf-8', 'gb2312');
        /*
         * 取得使用 TrueType 字体的文本的范围
         */
        $TEMP = imagettfbbox($this->FONT_SIZE, 0, $this->FONT_PATH, $this->WORD);
        $WORD_LENGTH = strlen($this->WORD);
        $WORD_WIDTH = $TEMP [2] - $TEMP [6];
        $WORD_HEIGHT = $TEMP [3] - $TEMP [7];
        /*
         * 文字水印的默认位置为右下角
         */
        if ($this->WORD_X == "") {
            $this->WORD_X = $this->PICTURE_WIDTH - $WORD_WIDTH;
        }
        if ($this->WORD_Y == "") {
            $this->WORD_Y = $this->PICTURE_HEIGHT - $WORD_HEIGHT;
        }
        imagesettile($this->TRUE_COLOR, $this->PICTURE_CREATE);
        imagefilledrectangle($this->TRUE_COLOR, 0, 0, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT, IMG_COLOR_TILED);
        $TEXT2 = imagecolorallocate($this->TRUE_COLOR, $this->RED, $this->GREEN, $this->Blue);
        imagettftext($this->TRUE_COLOR, $this->FONT_SIZE, $this->ANGLE, $this->WORD_X, $this->WORD_Y, $TEXT2, $this->FONT_PATH, $this->WORD);
    }

    /*
     * 水印图片
     */
    function _mark_picture()
    {

        /*
         * 获取水印图片的信息
         */
        @$SIZE = getimagesize($this->FORCE_URL);
        if (!$SIZE) {
            exit ($this->ERROR ['unalviable']);
        }
        $FORCE_PICTURE_;
        $FORCE_PICTURE_HEIGHT = $SIZE [1];
        // 创建水印图片
        switch ($SIZE [2]) {
            case 1 :
                $FORCE_PICTURE_CREATE = imagecreatefromgif($this->FORCE_URL);
                $FORCE_PICTURE_TYPE = "gif";
                break;
            case 2 :
                $FORCE_PICTURE_CREATE = imagecreatefromjpeg($this->FORCE_URL);
                $FORCE_PICTURE_TYPE = "jpg";
                break;
            case 3 :
                $FORCE_PICTURE_CREATE = imagecreatefrompng($this->FORCE_URL);
                $FORCE_PICTURE_TYPE = "png";
                break;
        }
        /*
         * 判断水印图片的大小，并生成目标图片的大小，如果水印比图片大，则生成图片大小为水印图片的大小。否则生成的图片大小为原图片大小。
         */
        $this->NEW_PICTURE = $this->PICTURE_CREATE;
        if ($FORCE_PICTURE_WIDTH > $this->PICTURE_WIDTH) {
            $CREATE_;
            gt;
            FORCE_START_X;
        } else {
            $CREATE_;
            gt;
            PICTURE_WIDTH;
        }
        if ($FORCE_PICTURE_HEIGHT > $this->PICTURE_HEIGHT) {
            $CREATE_HEIGHT = $FORCE_PICTURE_HEIGHT - $this->FORCE_START_Y;
        } else {
            $CREATE_HEIGHT = $this->PICTURE_HEIGHT;
        }
        /*
         * 创建一个画布
         */
        $NEW_PICTURE_CREATE = imagecreatetruecolor($CREATE_WIDTH, $CREATE_HEIGHT);
        $WHITE = imagecolorallocate($NEW_PICTURE_CREATE, 255, 255, 255);
        /*
         * 将背景图拷贝到画布中
         */
        imagecopy($NEW_PICTURE_CREATE, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);

        /*
         * 将目标图片拷贝到背景图片上
         */
        imagecopy($NEW_PICTURE_CREATE, $FORCE_PICTURE_CREATE, $this->FORCE_X, $this->FORCE_Y, $this->FORCE_START_X, $this->FORCE_START_Y, $FORCE_PICTURE_WIDTH, $FORCE_PICTURE_HEIGHT);
        $this->TRUE_COLOR = $NEW_PICTURE_CREATE;
    }

    // nd of mark
    function alpha_()
    {
        $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        $rgb = "#CDCDCD";
        $tran_color = "#000000";
        for ($j = 0; $j <= $this->PICTURE_HEIGHT - 1; $j++) {
            for ($i = 0; $i <= $this->PICTURE_WIDTH - 1; $i++) {
                $rgb = imagecolorat($this->PICTURE_CREATE, $i, $j);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $now_color = imagecolorallocate($this->PICTURE_CREATE, $r, $g, $b);
                if ($now_color == $tran_color) {
                    continue;
                } else {
                    $color = imagecolorallocatealpha($this->PICTURE_CREATE, $r, $g, $b, $ALPHA);
                    imagesetpixel($this->PICTURE_CREATE, $ALPHA_X + $i, $ALPHA_Y + $j, $color);
                }
                $this->TRUE_COLOR = $this->PICTURE_CREATE;
            }
        }
    }

    /*
     * 图片旋转: 沿y轴旋转
     */
    function turn_y()
    {
        $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        for ($x = 0; $x < $this->PICTURE_WIDTH; $x++) {
            imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, $this->PICTURE_WIDTH - $x - 1, 0, $x, 0, 1, $this->PICTURE_HEIGHT);
        }
    }

    /*
     * 沿X轴旋转
     */
    function turn_x()
    {
        $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        for ($y = 0; $y < $this->PICTURE_HEIGHT; $y++) {
            imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, $this->PICTURE_HEIGHT - $y - 1, 0, $y, $this->PICTURE_WIDTH, 1);
        }
    }

    /*
     * 任意角度旋转
     */
    function turn()
    {
        $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        imageCopyResized($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        $WHITE = imagecolorallocate($this->TRUE_COLOR, 255, 255, 255);
        $this->TRUE_COLOR = imagerotate($this->TRUE_COLOR, $this->CIRCUMROTATE, $WHITE);
    }

    /*
     * 图片锐化
     */
    function sharp()
    {
        $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        $cnt = 0;
        for ($x = 0; $x < $this->PICTURE_WIDTH; $x++) {
            for ($y = 0; $y < $this->PICTURE_HEIGHT; $y++) {
                $src_clr1 = imagecolorsforindex($this->TRUE_COLOR, imagecolorat($this->PICTURE_CREATE, $x - 1, $y - 1));
                $src_clr2 = imagecolorsforindex($this->TRUE_COLOR, imagecolorat($this->PICTURE_CREATE, $x, $y));
                $r = intval($src_clr2 ["red"] + $this->SHARP * ($src_clr2 ["red"] - $src_clr1 ["red"]));
                $g = intval($src_clr2 ["green"] + $this->SHARP * ($src_clr2 ["green"] - $src_clr1 ["green"]));
                $b = intval($src_clr2 ["blue"] + $this->SHARP * ($src_clr2 ["blue"] - $src_clr1 ["blue"]));
                $r = min(255, max($r, 0));
                $g = min(255, max($g, 0));
                $b = min(255, max($b, 0));
                if (($DST_CLR = imagecolorexact($this->PICTURE_CREATE, $r, $g, $b)) == -1)
                    $DST_CLR = imagecolorallocate($this->PICTURE_CREATE, $r, $g, $b);
                $cnt++;
                if ($DST_CLR == -1)
                    die ("color allocate faile at $x, $y ($cnt).");
                imagesetpixel($this->TRUE_COLOR, $x, $y, $DST_CLR);
            }
        }
    }

    /*
     * 将图片反色处理??
     */
    function return_color()
    {
        /*
         * 创建一个画布
         */
        $NEW_PICTURE_CREATE = imagecreate($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        $WHITE = imagecolorallocate($NEW_PICTURE_CREATE, 255, 255, 255);
        /*
         * 将背景图拷贝到画布中
         */
        imagecopy($NEW_PICTURE_CREATE, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
        $this->TRUE_COLOR = $NEW_PICTURE_CREATE;
    }

    /*
     * 生成目标图片并显示
     */
    function show()
    {
        // 判断浏览器,若是IE就不发送头
        if (isset ($_SERVER ['HTTP_USER_AGENT'])) {
            $ua = strtoupper($_SERVER ['HTTP_USER_AGENT']);
            if (!preg_match('/^.*MSIE.*\)$/i', $ua)) {
                header("Content-type:$this->PICTURE_MIME");
            }
        }
        $OUT = $this->PICTURE_TYPE;
        $OUT ($this->TRUE_COLOR);
    }

    /*
     * 生成目标图片并保存
     */
    function save_picture()
    {
        // 以 JPEG 格式将图像输出到浏览器或文件
        $OUT = $this->PICTURE_TYPE;
        if (function_exists($OUT)) {
            // 判断浏览器,若是IE就不发送头
            if (isset ($_SERVER ['HTTP_USER_AGENT'])) {
                $ua = strtoupper($_SERVER ['HTTP_USER_AGENT']);
                if (!preg_match('/^.*MSIE.*\)$/i', $ua)) {
                    header("Content-type:$this->PICTURE_MIME");
                }
            }
            if (!$this->TRUE_COLOR) {
                exit ($this->ERROR ['unavilable']);
            } else {
                $OUT ($this->TRUE_COLOR, $this->DEST_URL);
                $OUT ($this->TRUE_COLOR);
            }
        }
    }

    /*
     * 析构函数：释放图片
     */
    function __destruct()
    {
        /* 释放图片 */
        imagedestroy($this->TRUE_COLOR);
        imagedestroy($this->PICTURE_CREATE);
    }
    // nd of class
}