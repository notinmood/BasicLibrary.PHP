<?php

namespace Hiland\Environment;

class DeviceHelper
{
    /**
     * 判断当前运行的设备是否为移动设备
     * @return boolean
     */
    public static function isMobile(): bool
    {
        $useragent               = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $useragent_commentsBlock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';
        function checkExist($subStrings, $text)
        {
            foreach ($subStrings as $substr)
                if (false !== strpos($text, $substr)) {
                    return true;
                }
            return false;
        }

        $mobile_os_list    = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
        $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');

        $found_mobile = checkExist($mobile_os_list, $useragent_commentsBlock) || checkExist($mobile_token_list, $useragent);

        if ($found_mobile) {
            return true;
        } else {
            return false;
        }
    }
}
