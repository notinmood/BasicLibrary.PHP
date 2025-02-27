<?php

namespace Hiland\IO;

use Hiland\Web\MimeHelper;

/**
 * 图片操作工具类
 */
class ImageHelper
{
    /**
     * 判断给定的文件是否为图片
     * @param $fileName
     * @return bool
     */
    public static function isImage($fileName): bool
    {
        $result = getimagesize($fileName);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取图片的宽度
     * @param $image mixed 图片路径或者图片资源
     * @return int
     */
    public static function getWidth(mixed $image): int
    {
        if (is_string($image)) {
            $image = self::loadImage($image);
        }
        return imagesx($image);
    }

    /**
     * 根据给定的图片全路径，将图片载入内存
     * @param string $imageFileFullName 图片全路径名称
     * @param string $imageType 图片类型（jpg,png等，默认为空的时候系统自动推断图片类型，或者设置一个未知的类型的时候系统使用file_get_contents载入图片）
     * @return resource 内存中的图片资源
     */
    public static function loadImage(string $imageFileFullName, string $imageType = ''): bool
    {
        if (empty($imageType)) {
            $imageType = self::getImageType($imageFileFullName);
        }

        switch ($imageType) {
            case 'png':
                $image = imagecreatefrompng($imageFileFullName);
                break;
            case 'wbmp':
                $image = imagecreatefromwbmp($imageFileFullName);
                break;
            case 'gif':
                $image = imagecreatefromgif($imageFileFullName);
                break;
            case 'jpg':
                $image = imagecreatefromjpeg($imageFileFullName);
                break;
            case 'bmp':
                $image = self::imageCreateFromBMP($imageFileFullName);
                break;
            default:
                // file_get_contents函数要求php版本>4.3.0
                $srcData = '';
                if (function_exists("file_get_contents")) {
                    $srcData = file_get_contents($imageFileFullName);
                } else {
                    $handle = fopen($imageFileFullName, "r");
                    while (!feof($handle)) {
                        $srcData .= fgets($handle, 4096);
                    }
                    fclose($handle);
                }
                if (empty($srcData)) {
                    die("图片源为空");
                }
                $image = @imagecreatefromstring($srcData);
                break;
        }
        return $image;
    }

    /**
     * 获取图片的类型
     * @param string $imageFileFullName 文件全路径
     * @return string
     */
    public static function getImageType(string $imageFileFullName): string
    {
        if (extension_loaded('exif')) {
            return self::getImageTypeFromExif($imageFileFullName);
        } else {
            return self::getImageTypeFromImageSize($imageFileFullName);
        }
    }

    /**
     * 获取图片的类型
     * ────────────────────────
     *  php.ini中需要开通这个两个扩展模块
     *     - extension=php_mbstring.dll
     *     - extension=php_exif.dll
     * @param string $imageFileFullName 文件全路径
     * @return string
     */
    private static function getImageTypeFromExif(string $imageFileFullName): string
    {
        $result = 'jpg';
        $out    = exif_imagetype($imageFileFullName);

        switch ($out) {
            case 1:// IMAGE-TYPE_GIF
                $result = 'gif';
                break;
            case 2://	IMAGE-TYPE_JPEG
                $result = 'jpg';
                break;
            case 3://	IMAGE-TYPE_PNG
                $result = 'png';
                break;
            case 4:// 	IMAGE-TYPE_SWF
                $result = 'swf';
                break;
            case 5:// 	IMAGE-TYPE_PSD
                $result = 'psd';
                break;
            case 6 ://	IMAGE-TYPE_BMP
                $result = 'bmp';
                break;
            case 7 ://	IMAGE-TYPE_TIFF_II（Intel 字节顺序）
                $result = 'tiff';
                break;
            case 8 ://	IMAGE-TYPE_TIFF_MM（Motorola 字节顺序）
                $result = 'tiff';
                break;
            case 9:// 	IMAGE-TYPE_JPC
                $result = 'jpc';
                break;
            case 10 ://	IMAGE-TYPE_JP2
                $result = 'jp2';
                break;
            case 11 ://	IMAGE-TYPE_JPX
                $result = 'jpx';
                break;
            case 12 ://	IMAGE-TYPE_JB2
                $result = 'gb2';
                break;
            case 13:// 	IMAGE-TYPE_SWC
                $result = 'swc';
                break;
            case 14 ://	IMAGE-TYPE_IFF
                $result = 'iff';
                break;
            case 15 ://	IMAGE-TYPE_WBMP
                $result = 'wbmp';
                break;
            case 16:// 	IMAGE-TYPE_XBM
                $result = 'xbm';
                break;
        }

        return $result;
    }

    /**
     * 获取图片的类型
     * @param string $imageFileFullName 文件全路径
     * @return string
     */
    private static function getImageTypeFromImageSize(string $imageFileFullName): string
    {
        $array = getimagesize($imageFileFullName);
        // 索引 2 给出的是图像的类型，返回的是数字，
        // 其中1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，
        // 6 = BMP，7 = TIFF(intel byte order)，
        // 8 = TIFF(motorola byte order)，9 = JPC，
        // 10 = JP2，11 = JPX，12 = JB2，13 = SWC，
        // 14 = IFF，15 = WBMP，16 = XBM

        // 索引 mime 给出的是图像的 MIME信息(例如image/jpeg)，此信息可以用来
        // 在 HTTP Content-type 头信息中发送正确的信息，如：
        // header("Content-type: image/jpeg");

        switch ($array[2]) {
            case 1:
                $result = 'gif';
                break;
            case 3:
                $result = 'png';
                break;
            case 4:
                $result = 'swf';
                break;
            case 5:
                $result = 'psd';
                break;
            case 6:
                $result = 'bmp';
                break;
            case 15:
                $result = 'wbmp';
                break;
            case 7:
            case 8:
                $result = 'tiff';
                break;
            case 2:
            default:
                $result = 'jpg';
                break;
        }

        return $result;
    }

    /**
     * 加载bmb格式的图片进入内存成为资源
     * 此方法谨慎使用，有bug容易内存溢出
     * @param $fileFullName
     * @return bool|resource
     */
    public static function imageCreateFromBMP($fileFullName): bool
    {
        if (!$f1 = fopen($fileFullName, "rb")) {
            return FALSE;
        }

        $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
        if ($FILE['file_type'] != 19778)
            return FALSE;

        $BMP           = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
        $BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
        if ($BMP['size_bitmap'] == 0)
            $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
        $BMP['bytes_per_pixel']  = $BMP['bits_per_pixel'] / 8;
        $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
        $BMP['decal']            = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal']            -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal']            = 4 - (4 * $BMP['decal']);
        if ($BMP['decal'] == 4)
            $BMP['decal'] = 0;

        $PALETTE = array();
        if ($BMP['colors'] < 16777216 && $BMP['colors'] != 65536) {
            $PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
        }

        $IMG  = fread($f1, $BMP['size_bitmap']);
        $VIDE = chr(0);

        $res = imagecreatetruecolor($BMP['width'], $BMP['height']);
        $P   = 0;
        $Y   = $BMP['height'] - 1;
        while ($Y >= 0) {
            $X = 0;
            while ($X < $BMP['width']) {
                if ($BMP['bits_per_pixel'] == 32) {
                    $COLOR = unpack("V", substr($IMG, $P, 3));
                    $B     = ord(substr($IMG, $P, 1));
                    $G     = ord(substr($IMG, $P + 1, 1));
                    $R     = ord(substr($IMG, $P + 2, 1));
                    $color = imagecolorexact($res, $R, $G, $B);
                    if ($color == -1)
                        $color = imagecolorallocate($res, $R, $G, $B);
                    $COLOR[0] = $R * 256 * 256 + $G * 256 + $B;
                    $COLOR[1] = $color;
                } elseif ($BMP['bits_per_pixel'] == 24) {
                    $COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
                } elseif ($BMP['bits_per_pixel'] == 16) {
                    $COLOR    = unpack("v", substr($IMG, $P, 2));
                    $blue     = (($COLOR[1] & 0x001f) << 3) + 7;
                    $green    = (($COLOR[1] & 0x03e0) >> 2) + 7;
                    $red      = (($COLOR[1] & 0xfc00) >> 7) + 7;
                    $COLOR[1] = $red * 65536 + $green * 256 + $blue;
                } elseif ($BMP['bits_per_pixel'] == 8) {
                    $COLOR    = unpack("n", $VIDE . substr($IMG, $P, 1));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 4) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 2) % 2 == 0)
                        $COLOR[1] = ($COLOR[1] >> 4);
                    else
                        $COLOR[1] = ($COLOR[1] & 0x0F);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 1) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 8) % 8 == 0)
                        $COLOR[1] = $COLOR[1] >> 7;
                    elseif (($P * 8) % 8 == 1)
                        $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
                    elseif (($P * 8) % 8 == 2)
                        $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
                    elseif (($P * 8) % 8 == 3)
                        $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
                    elseif (($P * 8) % 8 == 4)
                        $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
                    elseif (($P * 8) % 8 == 5)
                        $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
                    elseif (($P * 8) % 8 == 6)
                        $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
                    elseif (($P * 8) % 8 == 7)
                        $COLOR[1] = ($COLOR[1] & 0x1);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } else
                    return FALSE;
                imagesetpixel($res, $X, $Y, $COLOR[1]);
                $X++;
                $P += $BMP['bytes_per_pixel'];
            }
            $Y--;
            $P += $BMP['decal'];
        }
        fclose($f1);

        return $res;
    }

    /**
     * 获取图片的高度
     * @param mixed $image 图片路径或者图片资源
     * @return int
     */
    public static function getHeight($image): int
    {
        if (is_string($image)) {
            $image = self::loadImage($image);
        }
        return imagesy($image);
    }

    /**
     * 实现等比例不失真缩放图片缩放
     * (在本函数调用的地方，使用完成后请使用 imagedestroy($newimage) 对新资源进行销毁)
     * @param resource $sourceImage 原来的图片资源
     * @param int $targetMaxWidth 图片放缩后允许的最多宽度
     * @param int $targetMaxHeight 图片放缩后允许的最多高度
     * @return resource 按比例放缩后的图片
     */
    public static function resizeImage($sourceImage, int $targetMaxWidth, int $targetMaxHeight)
    {
        $sourceWidth  = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        if (($targetMaxWidth && $sourceWidth > $targetMaxWidth) || ($targetMaxHeight && $sourceHeight > $targetMaxHeight)) {

            $resizeWidthTag  = false;
            $resizeHeightTag = false;
            $widthRatio      = 1;
            $heightRatio     = 1;
            $ratio           = 1;

            if ($targetMaxWidth && $sourceWidth > $targetMaxWidth) {
                $widthRatio     = $targetMaxWidth / $sourceWidth;
                $resizeWidthTag = true;
            }

            if ($targetMaxHeight && $sourceHeight > $targetMaxHeight) {
                $heightRatio     = $targetMaxHeight / $sourceHeight;
                $resizeHeightTag = true;
            }

            if ($resizeWidthTag && $resizeHeightTag) {
                $ratio = min($widthRatio, $heightRatio);
            }

            if ($resizeWidthTag && !$resizeHeightTag)
                $ratio = $widthRatio;
            if ($resizeHeightTag && !$resizeWidthTag)
                $ratio = $heightRatio;

            $newWidth  = $sourceWidth * $ratio;
            $newHeight = $sourceHeight * $ratio;

            if (function_exists("imagecopyresampled")) {
                $newImage = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
            } else {
                $newImage = imagecreate($newWidth, $newHeight);
                imagecopyresized($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
            }
            return $newImage;
        } else {
            return $sourceImage;
        }
    }

    /**
     * 裁剪图片
     * @param resource $sourceImage
     *            待操作的图片资源
     * @param int $topRemoveValue
     *            清除图片上部的数值（像素）
     * @param int $bottomRemoveValue
     *            清除图片下部的数值（像素）
     * @param int $leftRemoveValue
     *            清除图片左部的数值（像素）
     * @param int $rightRemoveValue
     *            清除图片右部的数值（像素）
     * @return resource
     */
    public static function cropImage($sourceImage, int $topRemoveValue, int $bottomRemoveValue = 0, int $leftRemoveValue = 0, int $rightRemoveValue = 0)
    {
        $sourceWidth  = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        if ($topRemoveValue >= $sourceHeight) {
            $topRemoveValue = 0;
        }

        if ($leftRemoveValue >= $sourceWidth) {
            $leftRemoveValue = 0;
        }

        if ($bottomRemoveValue >= $sourceHeight - $topRemoveValue) {
            $bottomRemoveValue = 0;
        }

        if ($rightRemoveValue >= $sourceWidth - $leftRemoveValue) {
            $rightRemoveValue = 0;
        }

        $newWidth     = $sourceWidth - $leftRemoveValue - $rightRemoveValue;
        $newHeight    = $sourceHeight - $topRemoveValue - $bottomRemoveValue;
        $croppedImage = imagecreatetruecolor($newWidth, $newHeight);

        imagecopy($croppedImage, $sourceImage, 0, 0, $leftRemoveValue, $topRemoveValue, $newWidth, $newHeight);

        return $croppedImage;
    }

    /**
     * 在浏览器中显示图片
     * @param resource $image
     * @param string $imageType
     * @param int $imageDisplayQuality 图片质量，jpg格式适用。取值范围0-100，默认为100
     */
    public static function display($image, string $imageType = 'jpg', int $imageDisplayQuality = 100)
    {
        $functionName = self::getImageOutputFunction($imageType);

        if (function_exists($functionName)) {
            // 判断浏览器,若是IE就不发送头
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $ua = strtoupper($_SERVER['HTTP_USER_AGENT']);
                if (!preg_match('/^.*MSIE.*\)$/i', $ua)) {
                    $contentType = MimeHelper::getMime($imageType);
                    ob_clean();
                    header("Content-type:$contentType");
                }
            }

            $functionName($image, null, $imageDisplayQuality);
        }
    }

    /**
     * 根据图片文件的扩展名称，确定图片的输出函数
     * @param string $imageExtensionFileNameWithoutDot
     *            不带小数点的图片扩展名称
     * @return string
     */
    public static function getImageOutputFunction(string $imageExtensionFileNameWithoutDot): string
    {
        $result = self::getImageFunctionInfo($imageExtensionFileNameWithoutDot, 'output');
        return $result;
    }

    private static function getImageFunctionInfo($imageExtensionFileNameWithoutDot, $functionType)
    {
        $arrayFunctions = self::ImageFunctionArray();
        $extFunctions   = $arrayFunctions[$imageExtensionFileNameWithoutDot];
        return $extFunctions[$functionType];
    }

    /**
     * 获取图片操作函数数组
     * @return array
     */
    private static function ImageFunctionArray(): array
    {
        return array(
            'jpg' => array(
                'output' => 'imagejpeg',
                'outputParamCount' => 3,
                'create' => 'imagecreatefromjpeg',
            ),
            'jpeg' => array(
                'output' => 'imagejpeg',
                'outputParamCount' => 3,
                'create' => 'imagecreatefromjpeg',
            ),
            'png' => array(
                'output' => 'imagepng',
                'outputParamCount' => 2,
                'create' => 'imagecreatefrompng',
            ),
            'gif' => array(
                'output' => 'imagegif',
                'outputParamCount' => 2,
                'create' => 'imagecreatefromgif',
            ),
            'bmp' => array(
                'output' => 'imagejpeg',
                'outputParamCount' => 3,
                'create' => 'imagecreatefromwbmp' //这个方法有问题
            ),
            'wbmp' => array(
                'output' => 'image2wbmp',
                'outputParamCount' => 2,
                'create' => 'imagecreatefromwbmp',
            ),
        );
    }

    /**
     * 根据图片文件的扩展名称，确定图片的载入函数
     * @param string $imageExtensionFileNameWithoutDot
     *            不带小数点的图片扩展名称
     * @return string
     */
    public static function getImageCreateFunction(string $imageExtensionFileNameWithoutDot): string
    {
        return self::getImageFunctionInfo($imageExtensionFileNameWithoutDot, 'create');
    }

    /**
     * 保存到物理绝对路径中
     * @param resource $image
     * @param int $imageDisplayQuality 图片质量，jpg格式适用。取值范围0-100，默认为100
     * @param string $filePhysicalFullName 要保存的图片的物理路径全名称（物理路径、文件名和扩展名）
     * @return string 被保存的图片的物理路径全名称（物理路径、文件名和扩展名）
     */
    public static function save($image, string $filePhysicalFullName, int $imageDisplayQuality = 80): string
    {
        $filePhysicalFullName = str_replace('/', '\\', $filePhysicalFullName);
        $imageType            = strtolower(FileHelper::getExtensionName($filePhysicalFullName));

        $functionName = self::getImageOutputFunction($imageType);
        $paramCount   = self::getImageOutputFunctionParamCount($imageType);

        if (function_exists($functionName)) {
            switch ($paramCount) {
                case 3:
                {
                    $functionName($image, $filePhysicalFullName, $imageDisplayQuality);
                    break;
                }
                default:
                {
                    $functionName($image, $filePhysicalFullName);
                }
            }
        }

        return $filePhysicalFullName;
    }

    /**
     * 根据图片文件的扩展名称，确定图片的输出函数
     * @param string $imageExtensionFileNameWithoutDot
     *            不带小数点的图片扩展名称
     * @return string
     */
    public static function getImageOutputFunctionParamCount($imageExtensionFileNameWithoutDot): string
    {
        return self::getImageFunctionInfo($imageExtensionFileNameWithoutDot, 'outputParamCount');
    }

    public static function fillText2Image($backGroundImage, $fontSize, $angle, $startX, $startY, $lineWidth, $textColor, $fontFileName, $content, $linesDistance, $firstLineIndent = 0)
    {
        $length = mb_strlen($content);

        $lineNumber  = 0;
        $charNumber  = 0;
        $lineContent = '';
        for ($i = 0; $i < $length; $i++) {
            $lineContent .= mb_substr($content, $i, 1);
            $charNumber++;
            $data         = imagettfbbox($fontSize, $angle, $fontFileName, $lineContent);
            $currentWidth = $data[2] - $data[0];
            if ($lineNumber == 0) {
                $currentWidth += $firstLineIndent;
            }
            if ($currentWidth > $lineWidth || $i == $length - 1) {
                $posY = $startY + $lineNumber * $linesDistance;
                $posX = $startX;
                if ($lineNumber == 0) {
                    $posX = $startX + $firstLineIndent;
                }
                imagefttext($backGroundImage, $fontSize, $angle, $posX, $posY, $textColor, $fontFileName, $lineContent);
                $charNumber = 0;
                $lineNumber++;
                $lineContent = '';
            }
        }
    }

    public static function fillText2Image2($backGroundImage, $fontSize, $angle, $startX, $startY, $textColor, $fontFileName, $content, $charCountPerLine, $linesDistance, $noFirstLineStretch = 0)
    {
        $length = mb_strlen($content);

        $lineNumber  = 0;
        $charNumber  = 0;
        $lineContent = '';
        for ($i = 0; $i < $length; $i++) {
            $lineContent .= mb_substr($content, $i, 1);
            $charNumber++;
            if ($charNumber > $charCountPerLine || $i == $length - 1) {
                $posY = $startY + $lineNumber * $linesDistance;
                $posX = $startX;
                if ($lineNumber > 0) {
                    $posX = $startX - $noFirstLineStretch;
                }
                imagefttext($backGroundImage, $fontSize, $angle, $posX, $posY, $textColor, $fontFileName, $lineContent);
                $charNumber = 0;
                $lineNumber++;
                $lineContent = '';
            }
        }
    }

    public static function imagebmp(&$im, $filename = '', $bit = 8, $compression = 0)
    {
        if (!in_array($bit, array(1, 4, 8, 16, 24, 32), true)) {
            $bit = 8;
        } else if ($bit === 32) // todo:32 bit
        {
            $bit = 24;
        }

        $bits = pow(2, $bit);

        // 调整调色板
        imagetruecolortopalette($im, true, $bits);
        $width      = imagesx($im);
        $height     = imagesy($im);
        $colors_num = imagecolorstotal($im);

        if ($bit <= 8) {
            // 颜色索引
            $rgb_quad = '';
            for ($i = 0; $i < $colors_num; $i++) {
                $colors   = imagecolorsforindex($im, $i);
                $rgb_quad .= chr($colors['blue']) . chr($colors['green']) . chr($colors['red']) . "\0";
            }

            // 位图数据
            $bmp_data = '';

            // 非压缩
            if ($compression === 0 || $bit < 8) {
                if (!in_array($bit, array(1, 4, 8))) {
                    $bit = 8;
                }

                $compression = 0;

                // 每行字节数必须为4的倍数，补齐。
                $extra   = '';
                $padding = 4 - ceil($width / (8 / $bit)) % 4;
                if ($padding % 4 != 0) {
                    $extra = str_repeat("\0", $padding);
                }

                for ($j = $height - 1; $j >= 0; $j--) {
                    $i = 0;
                    while ($i < $width) {
                        $bin   = 0;
                        $limit = $width - $i < 8 / $bit ? (8 / $bit - $width + $i) * $bit : 0;

                        for ($k = 8 - $bit; $k >= $limit; $k -= $bit) {
                            $index = imagecolorat($im, $i, $j);
                            $bin   |= $index << $k;
                            $i++;
                        }

                        $bmp_data .= chr($bin);
                    }

                    $bmp_data .= $extra;
                }
            } // RLE8 压缩
            else if ($compression == 1 && $bit == 8) {
                for ($j = $height - 1; $j >= 0; $j--) {
                    $last_index = "\0";
                    $same_num   = 0;
                    for ($i = 0; $i <= $width; $i++) {
                        $index = imagecolorat($im, $i, $j);
                        if ($index !== $last_index || $same_num > 255) {
                            if ($same_num != 0) {
                                $bmp_data .= chr($same_num) . chr($last_index);
                            }

                            $last_index = $index;
                            $same_num   = 1;
                        } else {
                            $same_num++;
                        }
                    }

                    $bmp_data .= "\0\0";
                }

                $bmp_data .= "\0\1";
            }

            $size_quad = strlen($rgb_quad);
            $size_data = strlen($bmp_data);
        } else {
            // 每行字节数必须为4的倍数，补齐。
            $extra   = '';
            $padding = 4 - ($width * ($bit / 8)) % 4;
            if ($padding % 4 != 0) {
                $extra = str_repeat("\0", $padding);
            }

            // 位图数据
            $bmp_data = '';

            for ($j = $height - 1; $j >= 0; $j--) {
                for ($i = 0; $i < $width; $i++) {
                    $index  = imagecolorat($im, $i, $j);
                    $colors = imagecolorsforindex($im, $index);

                    if ($bit == 16) {
                        $bin = 0 << $bit;

                        $bin |= ($colors['red'] >> 3) << 10;
                        $bin |= ($colors['green'] >> 3) << 5;
                        $bin |= $colors['blue'] >> 3;

                        $bmp_data .= pack("v", $bin);
                    } else {
                        $bmp_data .= pack("c*", $colors['blue'], $colors['green'], $colors['red']);
                    }

                    // todo: 32bit;
                }

                $bmp_data .= $extra;
            }

            $size_quad  = 0;
            $size_data  = strlen($bmp_data);
            $colors_num = 0;
        }

        // 位图文件头
        $file_header = "BM" . pack("V3", 54 + $size_quad + $size_data, 0, 54 + $size_quad);

        // 位图信息头
        $info_header = pack("V3v2V*", 0x28, $width, $height, 1, $bit, $compression, $size_data, 0, 0, $colors_num, 0);

        // 写入文件
        if ($filename != '') {
            $fp = fopen($filename, "wb");

            fwrite($fp, $file_header);
            fwrite($fp, $info_header);
            fwrite($fp, $rgb_quad);
            fwrite($fp, $bmp_data);
            fclose($fp);

            return true;
        }
    }
}
