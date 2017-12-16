<?php
namespace Hiland\Biz\Geo;

use Hiland\Utils\Web\NetHelper;

/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/12
 * Time: 7:48
 */
class GeoHelper
{
    /**
     * 地球半径（单位：KM）
     */
    const EARTH_RADIUS = 6378.137;

    /**
     * 根据给定的用户坐标，对包含坐标数据的数据集信息进行排序
     * @param float $userLat 用户纬度
     * @param float $userLng 用户经度
     * @param array $dataList 包含坐标数据的数据集信息
     * @param string $rankType 排序方式 asc升序 desc 降序
     * @param string $dataItemLatFormat 获取$dataList数据集内元素的lat坐标的格式（缺省为"lat"，如果此坐标嵌套在元素的子元素内，其格式为 "**"."lat"）
     * @param string $dataItemLngFormat 获取$dataList数据集内元素的lng坐标的格式（缺省为"lng"，如果此坐标嵌套在元素的子元素内，其格式为 "**"."lng"）
     * @return array|bool
     */
    public static function rankDistance($userLat, $userLng, $dataList, $rankType = 'asc', $dataItemLatFormat = "lat", $dataItemLngFormat = "lng")
    {
        if (!empty($userLat) && !empty($userLng)) {
            foreach ($dataList as $row) {
                $latArray = explode(".", $dataItemLatFormat);
                $itemLat = $row;
                foreach ($latArray as $item) {
                    $itemLat = $itemLat[$item];
                }

                $lngArray = explode(".", $dataItemLngFormat);
                $itemLng = $row;
                foreach ($lngArray as $item) {
                    $itemLng = $itemLng[$item];
                }

                $row['km'] = self::getDistance($userLat, $userLng, $itemLat, $itemLng);
                $row['km'] = round($row['km'], 1);

                $distance[] = $row['km'];
                $res[] = $row;
            }

            if (!empty($res)) {
                $rankType = strtoupper($rankType);
                if ($rankType == 'DESC') {
                    $rankType = SORT_DESC;
                } else {
                    $rankType = SORT_ASC;
                }
                array_multisort($distance, $rankType, $res);
                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //计算经纬度两点之间的距离
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $radLat1 = self::getRadian($lat1);
        $radLat2 = self::getRadian($lat2);
        $a = $radLat1 - $radLat2;
        $b = self::getRadian($lng1) - self::getRadian($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s1 = $s * self::EARTH_RADIUS;
        $s2 = round($s1 * 10000) / 10000;
        return $s2;
    }

    /**
     * 由角度计算弧度
     * @param float $angle 角度
     * @return float 弧度
     */
    public static function getRadian($angle)
    {
        return $angle * 3.1415926535898 / 180.0;
    }

    /**
     * @param $lat float
     * @param $lng float
     * @param string $serviceProviderName 地图提供商的名称
     * @param string $appKey
     * @return bool|array 获取失败返回false，获取成功返回的数组，其成员为
     * array(9) {
     * ["cityCode"] => int(172)
     * ["detailAddress"] => string(36) "山东省枣庄市薛城区深圳路"
     * ["business"] => string(0) ""
     * ["province"] => string(9) "山东省"
     * ["city"] => string(9) "枣庄市"
     * ["district"] => string(9) "薛城区"
     * ["street"] => string(9) "深圳路"
     * ["streetNumber"] => string(0) ""
     * ["adcode"] => string(6) "370403" //行政区编码
     * }
     */
    public static function getGeoInformation($lat, $lng, $serviceProviderName = 'amap', $appKey = '')
    {
        if (strtolower($serviceProviderName) == 'amap') {
            return self::getGeoInformationByAmap($lat, $lng, $appKey);
        } else {
            return self::getGeoInformationByBaidu($lat, $lng, $appKey);
        }
    }

    private static function getGeoInformationByAmap($lat, $lng, $appKey = '')
    {
        if (empty($appKey)) {
            $appKey = C('GEO_AMAP_AK');//获取高德地理信息的使用的appkey http://lbs.amap.com/dev
        }

        if (empty($appKey)) {
            $appKey = 'a75530497ceebeecb56e9dc2b933440c';
        }

        $url= "http://restapi.amap.com/v3/geocode/regeo?key=$appKey&location=$lng,$lat";

        $jsonString = NetHelper::request($url);
        $jsonArray = json_decode($jsonString, true);

        //return $jsonArray;

        if ($jsonArray) {
            if ($jsonArray['status'] == 1) {
                $resultDepart = $jsonArray['regeocode'];
                $addressDepart = $resultDepart['addressComponent'];

                $data['detailAddress'] = $resultDepart['formatted_address'];
                $data['cityCode'] = $addressDepart['citycode'];
                $data['business'] = '';

                $data['province'] = $addressDepart['province'];
                $data['city'] = $addressDepart['city'];
                $data['district'] = $addressDepart['district'];
                $data['adcode'] = $addressDepart['adcode'];

                $streetDepart=  $addressDepart['streetNumber'];
                $data['street'] = $streetDepart['street'];
                $data['streetNumber'] = $streetDepart['number'];

                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private static function getGeoInformationByBaidu($lat, $lng, $appKey = '')
    {
        if (empty($appKey)) {
            $appKey = C('GEO_BAIDU_AK');//获取百度地理信息的使用的appkey http://lbsyun.baidu.com/apiconsole/key
        }

        if (empty($appKey)) {
            $appKey = '4b89518117a374f0db7548bb816f88f4';
        }

        $url = "http://api.map.baidu.com/geocoder/v2/?ak=$appKey&location=$lat,$lng&output=json&pois=0";

        $jsonString = NetHelper::request($url);
        $jsonArray = json_decode($jsonString, true);

        if ($jsonArray) {
            if ($jsonArray['status'] == 0) {
                $resultDepart = $jsonArray['result'];
                $addressDepart = $resultDepart['addressComponent'];

                $data['cityCode'] = $resultDepart['cityCode'];
                $data['detailAddress'] = $resultDepart['formatted_address'];
                $data['business'] = $resultDepart['business'];

                $data['province'] = $addressDepart['province'];
                $data['city'] = $addressDepart['city'];
                $data['district'] = $addressDepart['district'];
                $data['street'] = $addressDepart['street'];
                $data['streetNumber'] = $addressDepart['street_number'];
                $data['adcode'] = $addressDepart['adcode'];

                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}