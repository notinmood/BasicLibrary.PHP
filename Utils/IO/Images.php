<?php

namespace Hiland\Utils\IO;

use Hiland\Utils\Data\ColorHelper;
use Hiland\Utils\Data\FontHelper;

/**
 *图像处理基础类
 * @author devel
 * @example 使用说明 分为三个步骤
 *          1、生成实例$image = new Images($url);
 *          2、设置各种信息$image->setMaskWord()等
 *          3、调用主方法$image->createImage
 *          示例如下：
 *          $url = 'http://www.sinaimg.cn/dy/slidenews/2_img/2016_05/72682_1705091_624995.jpg';
 *          $image = new Images($url);
 *
 *          $image->setMaskPosition(3);
 *
 *          $textfont = PHYSICAL_ROOT_PATH . C('WEIXIN_RECOMMEND_TEXTFONT');
 *
 *          $image->setMaskWord('Wecome to 山东润拓');
 *          $image->setMaskFont($textfont);
 *          $image->setMaskFontSize(20);
 *
 *          // $logo = PHYSICAL_ROOT_PATH . '/Public/Admin/images/login_logo.png';
 *          // $image->setMaskImage($logo);
 *          // $image->setMaskOffsetY(-30);
 *
 *          $image->setImageBorder();
 *
 *          // $image->flipX();
 *          // $image->flipY();
 *          // $image->flipY();
 *
 *          // $image->setCutType(2);
 *          // $image->setCutRectangle(600, 500);
 *          // $image->setCutPositionOnSourceImage(100,20);
 *
 *          $destImage = $image->createImage(600, 500);
 *          ImageHelper::display($destImage, 'jpg', 80);
 */
class Images
{
    // 原图资源信息
    var $sourceImage;

    // 目标文件
    var $dst_img;
    // // 图片资源句柄
    // var $sourceImage;
    // 新图句柄
    var $destImage;
    // 水印句柄
    var $maskImage;
    // 图片生成质量
    var $imageCreateQuality = 100;
    // 图片显示质量,默认为80
    var $imageDisplayQuality = 80;

    /**
     * 图片缩放比例（取值0-1之间表示缩小，1-X之间表示放大）
     *
     * @var int
     */
    var $imageScale = 1;
    // 原图宽度
    var $sourceImageWidth = 0;
    // 原图高度
    var $sourceImageHeight = 0;
    // 新图总宽度
    var $destImageWidth = 0;
    // 新图总高度
    var $destImageHeight = 0;
    // 填充图形宽
    var $filledImageWidth;
    // 填充图形高
    var $filledImageHeight;
    // 拷贝图形宽
    var $copyedImageWidth;
    // 拷贝图形高
    var $copyedImageHeight;
    // 原图绘制起始横坐标
    var $sourceImagePaintX = 0;
    // 原图绘制起始纵坐标
    var $sourceImagePaintY = 0;
    // 新图绘制起始横坐标
    var $destImagePaintX;
    // 新图绘制起始纵坐标
    var $destImagePaintY;
    // 水印文字
    var $maskWord;
    // 水印图片
    var $maskImageFileName;
    // 水印横坐标
    var $maskPositionX = 0;
    // 水印纵坐标
    var $maskPositionY = 0;
    // 水印横向偏移
    var $maskOffsetX = 5;
    // 水印纵向偏移
    var $maskOffsetY = 5;

    // 水印宽
    var $maskImageWidth;
    // 水印高
    var $maskImageHeight;
    // 水印文字主颜色
    var $maskFontMainColor = "#ffffff";
    // 水印文字侧颜色
    var $maskFontSideColor = "#888888";
    // 水印字体
    var $maskFont = 2;
    // 水印字体大小
    var $mastFontSize;
    // 水印位置
    var $maskPosition = 0;
    // 图片合并程度,值越大，合并程序越低
    var $maskImagePct = 50;
    // 文字合并程度,值越小，合并程序越低
    var $maskTxtPct = 50;
    // 图片边框尺寸
    var $imageBorderSize = 0;
    // 图片边框颜色
    var $imageBorderColor;
    // 水平翻转次数
    var $flipCountX = 0;
    // 垂直翻转次数
    var $flipCountY = 0;
    // 剪切类型
    var $cutType = 0;
    // 图片类型
    var $sourceImageType;

    /**
     * 传递过来的图片是资源信息还是文本路径信息
     *
     * @var bool
     */
    var $sourceImageIsResource = false;

    /**
     * 构造函数
     *
     * @param string $sourceImage
     *            待操作的图片信息，其可以为null，
     *            1、如果为null，则需要通过方法单独设置原图信息
     *            2、也可以是图片的全路径（如果是路径信息，那么系统可以推断第二个参数的类型）
     *            3、也可以是图片的资源信息(如果是图片的资源信息,必须设置第二个参数-图片的类型为jpg、gif、png等)
     * @param string $sourceImageType
     *            图片的类型为jpg、gif、png等不带小数点的扩展名
     */
    public function __construct($sourceImage = null, $sourceImageType = '')
    {
        $this->maskFontMainColor = "#ffffff";
        $this->maskFont = 2;
        $this->mastFontSize = 12;

        self::setSourceImage($sourceImage, $sourceImageType);
    }

    /**
     *
     * @param resource|string $sourceImage
     *            1、也可以是图片的全路径（如果是路径信息，那么系统可以推断第二个参数的类型）
     *            2、也可以是图片的资源信息(如果是图片的资源信息,必须设置第二个参数-图片的类型为jpg、gif、png等)
     * @param string $sourceImageType
     *            图片的类型为jpg、gif、png等不带小数点的扩展名
     */
    public function setSourceImage($sourceImage, $sourceImageType = '')
    {
        $this->sourceImageType = $sourceImageType;

        if (!empty($sourceImage)) {
            if (is_resource($sourceImage)) {
                $this->sourceImage = $sourceImage;
                $this->sourceImageIsResource = true;
            } else {
                $this->sourceImage = ImageHelper::loadImage($sourceImage);
                $this->sourceImageIsResource = false;
                if (empty($this->sourceImageType)) {
                    $this->sourceImageType = ImageHelper::getImageType($sourceImage);
                }
            }

            $this->sourceImageWidth = $this->getImageWidth($this->sourceImage);
            $this->sourceImageHeight = $this->getImageHeight($this->sourceImage);
        }
    }

    /**
     * 取得图片的宽
     * @param resource $image
     * @return int
     */
    function getImageWidth($image = null)
    {
        if (empty($image)) {
            $image = $this->sourceImage;
        }

        return imagesx($image);
    }

    /**
     * 取得图片的高
     * @param resource $image
     * @return int
     */
    function getImageHeight($image = null)
    {
        if (empty($image)) {
            $image = $this->sourceImage;
        }

        return imagesy($image);
    }

    /**
     * 设置图片的显示质量
     *
     * @param string $n
     *            质量
     */
    public function setImageDisplayQuality($n)
    {
        $this->imageDisplayQuality = (int)$n;
    }

    /**
     * 设置图片的生成质量
     *
     * @param string $n
     *            质量
     */
    public function setImageCreateQuality($n)
    {
        $this->imageCreateQuality = (int)$n;
    }

    /**
     * 设置水印文字
     *
     * @param string $word
     *            水印文字
     */
    public function setMaskWord($word)
    {
        $this->maskWord = $word;
    }

    /**
     * 设置水印字体信息
     *
     * @param int|string $font
     * @param int $size
     * @param string $mainColor 正面字体颜色
     * @param string $sideColor 侧面字体颜色
     */
    public function setMaskFontInfo($font = 2, $size = 12, $mainColor = "#ffffff", $sideColor = "#888888")
    {
        $this->setMaskFont($font);
        $this->setMaskFontSize($size);
        $this->setMaskFontColor($mainColor, $sideColor);
    }

    /**
     * 设置水印字体
     *
     * @param string|integer $font
     *            字体
     */
    public function setMaskFont($font = 2)
    {
        if (!is_numeric($font) && !file_exists($font)) {
            // die("字体文件不存在");
            $font = 2;
        }
        $this->maskFont = $font;
    }

    /**
     * 设置文字字体大小,仅对truetype字体有效
     * @param string $size
     */
    public function setMaskFontSize($size = "12")
    {
        $this->mastFontSize = $size;
    }

    /**
     * 设置字体颜色
     *
     * @param string $mainColor 正面字体颜色
     * @param string $sideColor 侧面字体颜色
     */
    public function setMaskFontColor($mainColor = "#ffffff", $sideColor = "#888888")
    {
        $this->maskFontMainColor = $mainColor;
        $this->maskFontSideColor = $sideColor;
    }

    /**
     * 设置图片水印
     *
     * @param string $imageFileName
     *            水印图片源
     */
    public function setMaskImage($imageFileName)
    {
        $this->maskImageFileName = $imageFileName;

        $this->maskImage = ImageHelper::loadImage($this->maskImageFileName);
        $this->maskImageWidth = $this->getImageWidth($this->maskImage);
        $this->maskImageHeight = $this->getImageHeight($this->maskImage);
    }

    /**
     * 设置水印横向偏移
     *
     * @param integer $x
     *            横向偏移量
     */
    public function setMaskOffsetX($x)
    {
        $this->maskOffsetX = (int)$x;
    }

    /**
     * 设置水印纵向偏移
     *
     * @param integer $y
     *            纵向偏移量
     */
    public function setMaskOffsetY($y)
    {
        $this->maskOffsetY = (int)$y;
    }

    /**
     * 指定水印位置
     *
     * @param integer $position
     *            位置,1:左上,2:左下,3:右上,0/4:右下
     */
    public function setMaskPosition($position = 0)
    {
        $this->maskPosition = (int)$position;
    }

    /**
     * 设置图片合并程度
     *
     * @param integer $n
     *            合并程度
     */
    public function setMaskImagePct($n)
    {
        $this->maskImagePct = (int)$n;
    }

    /**
     * 设置文字合并程度
     *
     * @param integer $n
     *            合并程度
     */
    public function setMaskTxtPct($n)
    {
        $this->maskTxtPct = (int)$n;
    }

    /**
     * 设置缩略图边框
     *
     * @param int $size
     * @param string $color
     */
    public function setImageBorder($size = 1, $color = "#000000")
    {
        $this->imageBorderSize = (int)$size;
        $this->imageBorderColor = $color;
    }

    /**
     * 水平翻转
     */
    public function flipX()
    {
        $this->flipCountX++;
    }

    /**
     * 垂直翻转
     */
    public function flipY()
    {
        $this->flipCountY++;
    }

    /**
     * 设置剪切类型
     *
     * @param int $type
     *            取值为
     *            0：X方向保持全图缩放，不裁切
     *            1：Y方向保持全图缩放，不裁切
     *            2：指定位置和大小后后手工裁切，X、Y方向均取得部分图像
     */
    public function setCutType($type)
    {
        $this->cutType = (int)$type;
    }

    /**
     * 图片输出
     */
    public function output()
    {
        ImageHelper::display($this->destImage, $this->sourceImageType, $this->imageDisplayQuality);
    }

    /**
     * 创建图片,主函数
     *
     * @param integer $a
     *            当缺少第二个参数时，此参数将用作百分比，缩放比例取值0-1之间表示缩小，1-X之间表示放大，
     *            否则作为宽度值
     * @param integer $b
     *            图片缩放后的高度
     * @return resource 调整后的图片信息，使用完成后请将其销毁
     */
    public function createImage($a, $b = null)
    {
        $num = func_num_args();
        if (1 == $num) {
            $r = (float)$a;
            $this->imageScale = $r;
            $this->setNewImageSize($r);
        }

        if (2 == $num) {
            $w = (int)$a;
            $h = (int)$b;
            if (0 == $w) {
                die("目标宽度不能为0");
            }
            if (0 == $h) {
                die("目标高度不能为0");
            }
            $this->setNewImageSize($w, $h);
        }

        if ($this->flipCountX % 2 != 0) {
            $this->_flipX($this->sourceImage);
        }

        if ($this->flipCountY % 2 != 0) {
            $this->_flipY($this->sourceImage);
        }
        $this->createMask();

        // 如果是传递过来的原图是文本路径信息，那么在系统内创建的图像资源需要内部销毁
        // 否则，如果传递过来的是资源信息，那么本系统内不能将其销毁
        if ($this->sourceImageIsResource == false) {
            imagedestroy($this->sourceImage);
        }
        return $this->destImage;
    }

    /**
     * 设置新图尺寸
     *
     * @param integer $newImageWidthOrScale
     *            目标宽度(或者缩放比例（取值0-1之间表示缩小，1-X之间表示放大），设置为缩放比例的时候，第二个参数省略)
     * @param integer $newImageHeight
     *            目标高度
     */
    private function setNewImageSize($newImageWidthOrScale, $newImageHeight = null)
    {
        $num = func_num_args();
        if (1 == $num) {
            $this->imageScale = $newImageWidthOrScale; // 如果只有一个参数，则第一个参数作为缩放比例
            // dump($this->sourceImageWidth .'--'.$this->imageScale);

            $this->filledImageWidth = round($this->sourceImageWidth * $this->imageScale) - $this->imageBorderSize * 2;
            $this->filledImageHeight = round($this->sourceImageHeight * $this->imageScale) - $this->imageBorderSize * 2;

            // 源文件起始坐标
            $this->sourceImagePaintX = 0;
            $this->sourceImagePaintY = 0;
            $this->copyedImageWidth = $this->sourceImageWidth;
            $this->copyedImageHeight = $this->sourceImageHeight;

            // 目标尺寸
            $this->destImageWidth = $this->filledImageWidth + $this->imageBorderSize * 2;
            $this->destImageHeight = $this->filledImageHeight + $this->imageBorderSize * 2;
        }

        if (2 == $num) {
            $tempFillWidth = (int)$newImageWidthOrScale - $this->imageBorderSize * 2;
            $tempFillHeight = (int)$newImageHeight - $this->imageBorderSize * 2;
            if ($tempFillWidth < 0 || $tempFillHeight < 0) {
                die("图片边框过大，已超过了图片的宽度");
            }
            $rate_w = $this->sourceImageWidth / $tempFillWidth;
            $rate_h = $this->sourceImageHeight / $tempFillHeight;

            switch ($this->cutType) {
                // 自动裁切 1：Y方向保持全图缩放，不裁切
                case 1:
                    {
                        // 如果图片是缩小剪切才进行操作
                        if ($rate_w >= 1 && $rate_h >= 1) {
                            if ($this->sourceImageWidth > $this->sourceImageHeight) {
                                $src_x = round($this->sourceImageWidth - $this->sourceImageHeight) / 2;
                                $this->setCutPositionOnSourceImage($src_x, 0);
                                $this->setCutRectangle($tempFillHeight, $tempFillHeight);

                                $this->copyedImageWidth = $this->sourceImageHeight;
                                $this->copyedImageHeight = $this->sourceImageHeight;
                            } elseif ($this->sourceImageWidth < $this->sourceImageHeight) {
                                $src_y = round($this->sourceImageHeight - $this->sourceImageWidth) / 2;
                                $this->setCutPositionOnSourceImage(0, $src_y);
                                $this->setCutRectangle($tempFillWidth, $tempFillHeight);

                                $this->copyedImageWidth = $this->sourceImageWidth;
                                $this->copyedImageHeight = $this->sourceImageWidth;
                            } else {
                                $this->setCutPositionOnSourceImage(0, 0);
                                $this->copyedImageWidth = $this->sourceImageWidth;
                                $this->copyedImageHeight = $this->sourceImageWidth;
                                $this->setCutRectangle($tempFillWidth, $tempFillHeight);
                            }
                        } else {
                            $this->setCutPositionOnSourceImage(0, 0);
                            $this->setCutRectangle($this->sourceImageWidth, $this->sourceImageHeight);

                            $this->copyedImageWidth = $this->sourceImageWidth;
                            $this->copyedImageHeight = $this->sourceImageHeight;
                        }

                        // 目标尺寸
                        $this->destImageWidth = $this->filledImageWidth + $this->imageBorderSize * 2;
                        $this->destImageHeight = $this->filledImageHeight + $this->imageBorderSize * 2;

                        break;
                    }

                // 手工裁切 2：指定位置和大小后后手工裁切，X、Y方向均取得部分图像
                case 2:
                    {
                        $this->copyedImageWidth = $this->filledImageWidth;
                        $this->copyedImageHeight = $this->filledImageHeight;

                        // 目标尺寸
                        $this->destImageWidth = $this->filledImageWidth + $this->imageBorderSize * 2;
                        $this->destImageHeight = $this->filledImageHeight + $this->imageBorderSize * 2;

                        break;
                    }
                // 自动裁切0：X方向保持全图缩放，不裁切
                case 0:
                default:
                    {
                        // 如果原图大于缩略图，产生缩小，否则不缩小
                        if ($rate_w < 1 && $rate_h < 1) {
                            $this->filledImageWidth = (int)$this->sourceImageWidth;
                            $this->filledImageHeight = (int)$this->sourceImageHeight;
                        } else {
                            if ($rate_w >= $rate_h) {
                                $this->filledImageWidth = (int)$tempFillWidth;
                                $this->filledImageHeight = round($this->sourceImageHeight / $rate_w);
                            } else {
                                $this->filledImageWidth = round($this->sourceImageWidth / $rate_h);
                                $this->filledImageHeight = (int)$tempFillHeight;
                            }
                        }

                        $this->sourceImagePaintX = 0;
                        $this->sourceImagePaintY = 0;

                        $this->copyedImageWidth = $this->sourceImageWidth;
                        $this->copyedImageHeight = $this->sourceImageHeight;

                        // 目标尺寸
                        $this->destImageWidth = $this->filledImageWidth + $this->imageBorderSize * 2;
                        $this->destImageHeight = $this->filledImageHeight + $this->imageBorderSize * 2;
                        break;
                    }
            }
        }

        // 目标文件起始坐标
        $this->destImagePaintX = $this->imageBorderSize;
        $this->destImagePaintY = $this->imageBorderSize;
    }

    /**
     * 设置源图剪切起始坐标点(仅在裁切模式cuteType为2手工裁切模式下有效)
     *
     * @param $x
     * @param $y
     */
    public function setCutPositionOnSourceImage($x, $y)
    {
        $this->sourceImagePaintX = (int)$x;
        $this->sourceImagePaintY = (int)$y;
    }

    /**
     * 设置图片剪切
     *
     * @param int $width
     *            矩形剪切的宽度
     * @param int $height 矩形剪切的高度
     */
    public function setCutRectangle($width, $height)
    {
        $this->filledImageWidth = (int)$width;
        $this->filledImageHeight = (int)$height;
    }

    /**
     * 水平翻转
     *
     * @param resource $src
     *            图片源
     */
    private function _flipX($src)
    {
        $src_x = $this->getImageWidth($src);
        $src_y = $this->getImageHeight($src);

        $new_im = imagecreatetruecolor($src_x, $src_y);
        for ($x = 0; $x < $src_x; $x++) {
            imagecopy($new_im, $src, $src_x - $x - 1, 0, $x, 0, 1, $src_y);
        }
        $this->sourceImage = $new_im;
    }

    /**
     * 垂直翻转
     *
     * @param resource $src
     *            图片源
     */
    private function _flipY($src)
    {
        $src_x = $this->getImageWidth($src);
        $src_y = $this->getImageHeight($src);

        $new_im = imagecreatetruecolor($src_x, $src_y);
        for ($y = 0; $y < $src_y; $y++) {
            imagecopy($new_im, $src, 0, $src_y - $y - 1, 0, $y, $src_x, 1);
        }
        $this->sourceImage = $new_im;
    }

    /**
     * 生成水印,调用了生成水印文字和水印图片两个方法
     */
    private function createMask()
    {
        $this->destImage = imagecreatetruecolor($this->destImageWidth, $this->destImageHeight);

        if ($this->maskWord) {
            // 获取水印文本所占用的高宽信息
            $maskImageSize = FontHelper::getSize($this->maskFont, $this->mastFontSize, $this->maskWord);
            $this->maskImageWidth = $maskImageSize[0];
            $this->maskImageHeight = $maskImageSize[1];

            if ($this->isMaskBiggerThanBackground()) {
                die("水印文字过大");
            } else {
                // $this->destImage = imagecreatetruecolor($this->destImageWidth, $this->destImageHeight);
                $white = imagecolorallocate($this->destImage, 255, 255, 255);
                imagefilledrectangle($this->destImage, 0, 0, $this->destImageWidth, $this->destImageHeight, $white); // 填充背景色

                $this->drawImageBorder();

                imagecopyresampled($this->destImage, $this->sourceImage, $this->destImagePaintX, $this->destImagePaintY, $this->sourceImagePaintX, $this->sourceImagePaintY, $this->filledImageWidth, $this->filledImageHeight, $this->copyedImageWidth, $this->copyedImageHeight);
                $this->createMaskWord($this->destImage);
            }
        }

        if ($this->maskImageFileName) {
            if ($this->isMaskBiggerThanBackground()) {
                // 将水印生成在原图上再拷
                $this->createMaskImage($this->sourceImage);
                // $this->destImage = imagecreatetruecolor($this->destImageWidth, $this->destImageHeight);
                $white = imagecolorallocate($this->destImage, 255, 255, 255);
                imagefilledrectangle($this->destImage, 0, 0, $this->destImageWidth, $this->destImageHeight, $white); // 填充背景色
                $this->drawImageBorder();
                imagecopyresampled($this->destImage, $this->sourceImage, $this->destImagePaintX, $this->destImagePaintY, $this->sourceImagePaintX, $this->sourceImagePaintY, $this->filledImageWidth, $this->destImagePaintY, $this->copyedImageWidth, $this->copyedImageHeight);
            } else {
                // 创建新图并拷贝
                // $this->destImage = imagecreatetruecolor($this->destImageWidth, $this->destImageHeight);
                $white = imagecolorallocate($this->destImage, 255, 255, 255);
                imagefilledrectangle($this->destImage, 0, 0, $this->destImageWidth, $this->destImageHeight, $white); // 填充背景色
                $this->drawImageBorder();
                imagecopyresampled($this->destImage, $this->sourceImage, $this->destImagePaintX, $this->destImagePaintY, $this->sourceImagePaintX, $this->sourceImagePaintY, $this->filledImageWidth, $this->filledImageHeight, $this->copyedImageWidth, $this->copyedImageHeight);
                $this->createMaskImage($this->destImage);
            }
        }

        if (empty($this->maskWord) && empty($this->maskImageFileName)) {
            // $this->destImage = imagecreatetruecolor($this->destImageWidth, $this->destImageHeight);
            $white = imagecolorallocate($this->destImage, 255, 255, 255);
            imagefilledrectangle($this->destImage, 0, 0, $this->destImageWidth, $this->destImageHeight, $white); // 填充背景色
            $this->drawImageBorder();

            imagecopyresampled($this->destImage, $this->sourceImage, $this->destImagePaintX, $this->destImagePaintY, $this->sourceImagePaintX, $this->sourceImagePaintY, $this->filledImageWidth, $this->filledImageHeight, $this->copyedImageWidth, $this->copyedImageHeight);
        }
    }

    /**
     * 检查水印图是否大于生成后的图片宽高
     */
    private function isMaskBiggerThanBackground()
    {
        Return ($this->maskImageWidth + $this->maskOffsetX > $this->filledImageWidth || $this->maskImageHeight + $this->maskOffsetY > $this->filledImageHeight) ? true : false;
    }

    /**
     * 画边框
     */
    private function drawImageBorder()
    {
        if (!empty($this->imageBorderSize)) {
            $c = ColorHelper::Hex2RGB($this->imageBorderColor);
            $color = imagecolorallocate($this->sourceImage, $c[0], $c[1], $c[2]);
            imagefilledrectangle($this->destImage, 0, 0, $this->destImageWidth, $this->destImageHeight, $color); // 填充背景色
        }
    }

    /**
     * 生成水印文字
     * @param $backGroundImage
     */
    private function createMaskWord($backGroundImage)
    {
        $this->calcMaskPosition();
        $this->checkMaskValid();

        $mainColorArray = ColorHelper::Hex2RGB($this->maskFontMainColor);
        $mainColor = imagecolorallocatealpha($backGroundImage, $mainColorArray[0], $mainColorArray[1], $mainColorArray[2], $this->maskTxtPct);

        $sideColorArray = ColorHelper::Hex2RGB($this->maskFontSideColor);
        $sideColor = imagecolorallocatealpha($backGroundImage, $sideColorArray[0], $sideColorArray[1], $sideColorArray[2], $this->maskTxtPct);

        // dump('text'.$this->maskPositionY);
        if (is_numeric($this->maskFont)) {
            imagestring($backGroundImage, $this->maskFont, $this->maskPositionX + 1, $this->maskPositionY + 1, $this->maskWord, $sideColor);
            imagestring($backGroundImage, $this->maskFont, $this->maskPositionX, $this->maskPositionY, $this->maskWord, $mainColor);
        } else {
            imagettftext($backGroundImage, $this->mastFontSize, 0, $this->maskPositionX + 1, $this->maskPositionY + 1, $sideColor, $this->maskFont, $this->maskWord);
            imagettftext($backGroundImage, $this->mastFontSize, 0, $this->maskPositionX, $this->maskPositionY, $mainColor, $this->maskFont, $this->maskWord);
        }
    }

    /**
     * 计算水印的位置坐标
     */
    private function calcMaskPosition()
    {
        if ($this->isMaskBiggerThanBackground()) {
            switch ($this->maskPosition) {
                case 1:
                    // 左上
                    $this->maskPositionX = $this->maskOffsetX + $this->imageBorderSize;
                    $this->maskPositionY = $this->maskOffsetY + $this->imageBorderSize + $this->maskImageHeight;
                    break;

                case 2:
                    // 左下
                    $this->maskPositionX = $this->maskOffsetX + $this->imageBorderSize;
                    $this->maskPositionY = $this->sourceImageHeight - $this->maskImageHeight - $this->maskOffsetY;
                    break;

                case 3:
                    // 右上
                    $this->maskPositionX = $this->sourceImageWidth - $this->maskImageWidth - $this->maskOffsetX;
                    $this->maskPositionY = $this->maskOffsetY + $this->imageBorderSize + $this->maskImageHeight;
                    break;

                case 4:
                    // 右下
                default:
                    // 默认将水印放到右下,偏移指定像素
                    $this->maskPositionX = $this->sourceImageWidth - $this->maskImageWidth - $this->maskOffsetX;
                    $this->maskPositionY = $this->sourceImageHeight - $this->maskImageHeight - $this->maskOffsetY;
                    break;
            }
        } else {
            switch ($this->maskPosition) {
                case 1:
                    // 左上
                    $this->maskPositionX = $this->maskOffsetX + $this->imageBorderSize;
                    $this->maskPositionY = $this->maskOffsetY + $this->imageBorderSize + $this->maskImageHeight;
                    break;

                case 2:
                    // 左下
                    $this->maskPositionX = $this->maskOffsetX + $this->imageBorderSize;
                    $this->maskPositionY = $this->destImageHeight - $this->maskImageHeight - $this->maskOffsetY - $this->imageBorderSize;
                    break;

                case 3:
                    // 右上
                    $this->maskPositionX = $this->destImageWidth - $this->maskImageWidth - $this->maskOffsetX - $this->imageBorderSize;
                    $this->maskPositionY = $this->maskOffsetY + $this->imageBorderSize + $this->maskImageHeight;
                    break;

                case 4:
                    // 右下
                default:
                    // 默认将水印放到右下,偏移指定像素
                    $this->maskPositionX = $this->destImageWidth - $this->maskImageWidth - $this->maskOffsetX - $this->imageBorderSize;
                    $this->maskPositionY = $this->destImageHeight - $this->maskImageHeight - $this->maskOffsetY - $this->imageBorderSize;
                    break;
            }
        }
    }

    /**
     * 检查水印图是否超过原图
     */
    private function checkMaskValid()
    {
        if ($this->maskImageWidth + $this->maskOffsetX > $this->sourceImageWidth || $this->maskImageHeight + $this->maskOffsetY > $this->sourceImageHeight) {
            die("水印图片尺寸大于原图，请缩小水印图");
        }
    }

    /**
     * 生成水印图
     * @param $backGroundImage
     */
    private function createMaskImage($backGroundImage)
    {
        $this->calcMaskPosition();
        $this->checkMaskValid();

        // dump('image'.$this->maskPositionY);
        imagecopymerge($backGroundImage, $this->maskImage, $this->maskPositionX, $this->maskPositionY, 0, 0, $this->maskImageWidth, $this->maskImageHeight, $this->maskImagePct);

        imagedestroy($this->maskImage);
    }
}