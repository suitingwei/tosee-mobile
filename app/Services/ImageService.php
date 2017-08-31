<?php

namespace App\Services;

use App\Models\GroupShootRule;
use Endroid\QrCode\QrCode;
use function Qiniu\base64_urlSafeDecode;

class ImageService
{
    /**
     * Generate the groupshoot merged four images when sharing the groupshoot with * thumbnail.
     * @see https://app.zeplin.io/project/58c7ffdcff98945ac51ca72a/screen/58f72ffa2f0a014982be9eec
     *
     * @param array  $imageUrls
     * @param string $direction
     */
    public static function generateMeredImage(array $imageUrls, $direction = GroupShootRule::CANVAS_DIRECTION_VERTICAL)
    {
        header('Content-Type: image/jpeg');
        $canvas          = imagecreatetruecolor(640, 646);
        $backgroundColor = imagecolorallocate($canvas, 44, 46, 50);
        imagefill($canvas, 0, 0, $backgroundColor);
        $startX       = 2;
        $startY       = 2;
        $imageWidth   = 318;
        $imageHeight  = 318;
        $marginWidth  = 6;
        $marginHeight = 6;
        $cropImage1   = self::cropGroupShootCover($imageUrls[0], $direction);
        imagecopymerge($canvas, $cropImage1, $startX, $startY, 0, 0, 318, 318, 100);

        $cropImage2 = self::cropGroupShootCover($imageUrls[1], $direction);
        imagecopymerge($canvas, $cropImage2, $startX + $imageWidth + $marginWidth, $startY, 0, 0, 318, 318, 100);

        $cropImage3 = self::cropGroupShootCover($imageUrls[2], $direction);
        imagecopymerge($canvas, $cropImage3, $startX, $startY + $imageHeight + $marginHeight, 0, 0, 318, 318, 100);

        $cropImage4 = self::cropGroupShootCover($imageUrls[3], $direction);
        imagecopymerge($canvas, $cropImage4, $startX + $imageWidth + $marginWidth, $startY + $imageHeight + $marginHeight, 0, 0, 318, 318, 100);

        imagejpeg($canvas);

        //Free the image memory.
        imagedestroy($canvas);
        imagedestroy($cropImage1);
        imagedestroy($cropImage2);
        imagedestroy($cropImage3);
        imagedestroy($cropImage4);
        exit();
    }

    /**
     * Crop the image into specific shape.
     *
     * @param        $fileUrl
     *
     * @param string $direction
     *
     * @return bool|resource
     */
    public static function cropGroupShootCover($fileUrl, $direction = GroupShootRule::CANVAS_DIRECTION_VERTICAL)
    {
        $image = imagecreatefromgif($fileUrl);
        list($width, $height) = getimagesize($fileUrl);

        if ($height >= $width) {
            $image = imagecrop($image, [
                'x'      => 0,
                'y'      => ($height - $width) / 2,
                'width'  => $width,
                'height' => $width,
            ]);
        }
        else {
            $image = imagecrop($image, [
                'x'      => ($width - $height) / 2,
                'y'      => 0,
                'width'  => $height,
                'height' => $height,
            ]);
        }
        return imagescale($image, 318, 318);
    }

    /**
     * Generate the qrcode image for the specfic url.
     *
     * @param $url
     *
     * @return string
     */
    public static function generateQrCode($url)
    {
        return (new QrCode())->setText(base64_urlSafeDecode($url))
                             ->setSize(150)
                             ->setPadding(0)
                             ->setErrorCorrection('high')
                             ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                             ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                             ->get('png');
    }

    /**
     * Generate the watermark text image.
     * We can not use the qiniu image api,because we need the watermark text with background color,
     * That's which qiniu not support.
     *
     * @param $text
     *
     * @return resource
     */
    public static function generateWatermarkTextImage($text)
    {
        header('Content-Type: image/png');

        $str                           = str_limit(self::removeEmoji($text), 120);
        $stringExplodedByLineSeparator = explode("\n", $str);
        $upHalfStr                     = $stringExplodedByLineSeparator[0];
        $downHalf                      = isset($stringExplodedByLineSeparator[1]) ? $stringExplodedByLineSeparator[1] : '';
        $upHalfLinesCount              = self::getLinesByStr($upHalfStr);
        $downHalfLinesCount            = self::getLinesByStr($downHalf);
        $lines                         = ($upHalfLinesCount + $downHalfLinesCount) <= 4 ? ($upHalfLinesCount + $downHalfLinesCount) : 4;
        $image                         = self::initWaterImageBase($lines);
        $white                         = imagecolorallocate($image, 255, 255, 255);
        $font                          = base_path() . '/app/PingFang-Bold.ttf';

        for ($i = 0; $i < $upHalfLinesCount; ++$i) {
            $start = $i * 13;
            $text  = mb_substr($upHalfStr, $start, 13);
            imagettftext($image, 35, 0, 42, 78 + ($i * 64), $white, $font, $text);
        }

        for ($downHalfIndex = 0; $downHalfIndex < $downHalfLinesCount; ++$downHalfIndex) {
            $start = $downHalfIndex * 13;
            $text  = mb_substr($downHalf, $start, 13);
            imagettftext($image, 35, 0, 42, 78 + (($i + $downHalfIndex) * 64), $white, $font, $text);
        }

        imagepng($image);

        //Free the image memory.
        imagedestroy($image);
    }

    private static function removeEmoji($text)
    {
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text     = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text   = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text     = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc  = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text    = preg_replace($regexDingbats, '', $clean_text);

        return $clean_text;
    }

    /**
     * @param $string
     *
     * @return int
     */
    private static function getLinesByStr($string)
    {
        return intval(ceil(mb_strlen($string) / 13));
    }

    /**
     * @param $lines
     *
     * @return resource
     */
    private static function initWaterImageBase($lines)
    {
        $height     = self::getWaterTextImageHeightByLines($lines);
        $image      = imagecreatetruecolor(720, $height);
        $background = imagecolorallocate($image, 237, 73, 86);
        imagefilledrectangle($image, 0, 0, 720, 300, $background);
        return $image;
    }

    /**
     * @param $lines
     *
     * @return int
     */
    private static function getWaterTextImageHeightByLines($lines)
    {
        switch ($lines) {
            case 1:
                return 125;
            case 2:
                return 185;
            case 3:
                return 265;
            case 4:
                return 295;
        }
    }

    /**
     * Crop the image into circle.
     *
     * @param $fileUrl
     *
     * @internal param $input
     */
    public static function cropIntoCircle($fileUrl)
    {
        header("Content-type: image/png");
        $width  = 100;
        $height = 100;
        //Scale to avatar into 100x100 square.
        $avatarImage = imagecreatefromjpeg($fileUrl);
        $avatarImage = imagescale($avatarImage, $width, $height);

        //Mask.
        $mask = imagecreatetruecolor($width, $height);
        $bg   = imagecolorallocate($mask, 255, 255, 255);
        imagefill($mask, 0, 0, $bg);


        $e = imagecolorallocate($mask, 0, 0, 0);
        $r = $width <= $height ? $width : $height;
        imagefilledellipse($mask, ($width / 2), ($height / 2), $r, $r, $e);
        imagecolortransparent($mask, $e);
        imagecopymerge($avatarImage, $mask, 0, 0, 0, 0, $width, $height, 100);
        imagecolortransparent($avatarImage, $bg);
        imagepng($avatarImage);
        imagedestroy($mask);
        imagedestroy($avatarImage);
    }

}
