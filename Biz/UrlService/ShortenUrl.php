<?php
namespace Hiland\Biz\UrlService;

use Hiland\Utils\Data\StringHelper;
use Hiland\Utils\DataModel\ModelMate;
use Hiland\Utils\Web\WebHelper;

/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/5/9
 * Time: 17:22
 */
//TODO  本逻辑尚未完成

class ShortenUrl
{
    const SHORTPATH = 'index.php/hiland/_sp/l/id/';

    public static function shorten($longUrl)
    {
        $data = array(
            'longurl' => $longUrl,
        );

        $mate = new ModelMate('shorturl');
        $result = $mate->interact($data);
        return 'http://' . WebHelper::getHostName() . self::SHORTPATH . $result;
    }

    public static function getLongUrl($shortUrl)
    {
        if (is_numeric($shortUrl)) {
            return self::getLongUrlDetails($shortUrl);
        }

        $originalUrl = $shortUrl;
        $shortUrl = strtolower($shortUrl);
        $partten = 'http://' . WebHelper::getHostName() . self::SHORTPATH;

        if (StringHelper::isStartWith($shortUrl, $partten)) {
            $id = StringHelper::getSeperatorAfterString($shortUrl, $partten);

            if (StringHelper::isEndWith($id, '/')) {
                $id = StringHelper::getSeperatorBeforeString($id, '/');
            }

            return self::getLongUrlDetails($id);
        } else {
            return $originalUrl;
        }
    }

    private function getLongUrlDetails($id)
    {
        $mate = new ModelMate('shorturl');
        $data = $mate->get($id);
        if (empty($data)) {
            return '';
        } else {
            return $data['longurl'];
        }
    }
}