<?php

namespace Hiland\Data;

class ChineseHelper
{
    /**
     * 将中文字符串转成拼音
     * @param string $chineseString 待转换的中文字符串
     * @param string $charCoding    中文编码（GBK 页面可改为 gb2312，其他随意填写为 UTF8）
     * @return string 转换后的拼音
     */
    static function getPinyin(string $chineseString, string $charCoding = 'UTF8'): string
    {
        $charCoding  = mb_strtolower($charCoding);
        $_DataKey    = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue  = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
            "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey   = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data       = array_combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        // reset($_Data);
        if ($charCoding != 'gb2312') {
            $chineseString = self::convertUTF8ToGB2312($chineseString);
        }

        $_Res = '';
        for ($i = 0; $i < strlen($chineseString); $i++) {
            $_P = ord(substr($chineseString, $i, 1));
            if ($_P > 160) {
                $_Q = ord(substr($chineseString, ++$i, 1));
                $_P = $_P * 256 + $_Q - 65536;
            }
            $_Res .= self:: getCharPinyin($_P, $_Data);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
    }

    /**
     * 将 utf8 编码的字符串转成 gb2312 编码的字符串
     * @param string $chineseStringInUTF8
     * @return string gb2312编码的字符串
     */
    public static function convertUTF8ToGB2312(string $chineseStringInUTF8): string
    {
        return iconv('UTF-8', 'GB2312', $chineseStringInUTF8);
    }

    /**
     * 将 gb2312 编码的字符串转成 utf8 编码的字符串
     * @param string $chineseStringInGB2312
     * @return string utf8编码的字符串
     */
    public static function convertGB2312ToUTF8(string $chineseStringInGB2312): string
    {
        return iconv('UTF-8', 'GB2312', $chineseStringInGB2312);
    }

    /**
     * 将源编码的字符串转成目标编码的字符串
     * @param string $stringData
     * @param string $fromEncoding
     * @param string $toEncoding
     * @return string 转码后的字符串
     */
    public static function convertEncoding(string $stringData, string $fromEncoding, string $toEncoding): string
    {
        return iconv($fromEncoding, $toEncoding, $stringData);
    }

    private static function getCharPinyin($_Num, $_Data)
    {
        if ($_Num > 0 && $_Num < 160) {
            return chr($_Num);
        } elseif ($_Num < -20319 || $_Num > -10247) {
            return '';
        } else {
            foreach ($_Data as $k => $v) {
                if ($v <= $_Num) break;
            }
            /** @noinspection PhpUndefinedVariableInspection */
            return $k;
        }
    }

    /**
     * 将汉字进行 unicode（UCS-2 类型）编码
     * @param string $data     待转码的原始中文字符串
     * @param string $encoding 原始字符串的编码，默认UTF-8
     * @param string $prefix   编码后的前缀，默认"\\u"
     * @param string $postfix  编码后的后缀，默认""
     * @return string unicode（UCS-2类型）编码后的字符串
     * @example
     *                         前缀和后缀通常有固定搭配,比如：
     *                         前缀 "\\u"通常搭配 后缀""
     *                         前缀 "&#"通常搭配 后缀";"
     */
    public static function unicodeEncode(string $data, string $encoding = 'UTF-8', string $prefix = '\u', string $postfix = ''): string
    {
        $name = iconv($encoding, 'UCS-2', $data);
        $len  = strlen($name);
        $str  = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2) {
            $c  = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0) {
                // 两个字节的文字
                $str .= $prefix . base_convert(ord($c2), 10, 16) . base_convert(ord($c), 10, 16) . $postfix;
            } else {
                $str .= $c2;
            }
        }
        return $str;
    }

    /**
     * 将 unicode（UCS-2 类型）解码为可识别汉字
     * @param string $data     待解码的unicode字符串
     * @param string $encoding 原始字符串的编码，默认UTF-8
     * @param string $prefix   编码后的前缀，默认"\\u"
     * @param string $postfix  编码后的后缀，默认""
     * @return string 解码后可识别汉字
     * @example
     *                         前缀和后缀通常有固定搭配,比如：
     *                         前缀 "\\u"通常搭配 后缀""
     *                         前缀 "&#"通常搭配 后缀";"
     *                         TODO: 英文信息经过编码再解码就不对了，需要修改
     */
    public static function unicodeDecode(string $data, string $encoding = 'UTF-8', string $prefix = '\u', string $postfix = ''): string
    {
        $data = str_replace($prefix, '\u', $data);
        $data = str_replace($postfix, '', $data);

        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $data, $matches);
        $name = '';
        if (!empty($matches)) {

            for ($j = 0; $j < count($matches[0]); $j++) {
                $str = $matches[0][$j];
                if (strpos($str, '\u') === 0) {
                    $code  = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c     = chr($code2) . chr($code);
                    $c     = iconv('UCS-2', $encoding, $c);
                    $name  .= $c;
                } else {
                    $name .= $str;
                }
            }
        }
        return $name;
    }


    /**
     * 获取中文首字拼音字母(例如L,M等)
     * @param string $chineseString 中文字符串
     * @return string
     */
    public static function getFirstChar(string $chineseString): string
    {
        //手动添加未识别记录
        if (mb_substr($chineseString, 0, 1, 'utf-8') == "怡") {
            return "Y";
        }

        if (mb_substr($chineseString, 0, 1, 'utf-8') == "泗") {
            return "S";
        }

        /**
         * 英文字符串的首字符
         */
        $firstChar = ord(substr($chineseString, 0, 1));
        if (($firstChar >= ord("a") and $firstChar <= ord("z")) or ($firstChar >= ord("A") and $firstChar <= ord("Z"))) {
            return strtoupper(chr($firstChar));
        }

        /**
         * 中文字符串的首字符
         */
        $stringEncoded = iconv("UTF-8", "GBK", $chineseString);
        $charCode      = ord($stringEncoded[0]) * 256 + ord($stringEncoded[1]) - 65536;

        if ($charCode >= -20319 and $charCode <= -20284) {
            return "A";
        }

        if ($charCode >= -20283 and $charCode <= -19776) {
            return "B";
        }

        if ($charCode >= -19775 and $charCode <= -19219) {
            return "C";
        }

        if ($charCode >= -19218 and $charCode <= -18711) {
            return "D";
        }

        if ($charCode >= -18710 and $charCode <= -18527) {
            return "E";
        }

        if ($charCode >= -18526 and $charCode <= -18240) {
            return "F";
        }

        if ($charCode >= -18239 and $charCode <= -17923) {
            return "G";
        }

        if ($charCode >= -17922 and $charCode <= -17418) {
            return "H";
        }

        if ($charCode >= -17417 and $charCode <= -16475) {
            return "J";
        }

        if ($charCode >= -16474 and $charCode <= -16213) {
            return "K";
        }

        if ($charCode >= -16212 and $charCode <= -15641) {
            return "L";
        }

        if ($charCode >= -15640 and $charCode <= -15166) {
            return "M";
        }

        if ($charCode >= -15165 and $charCode <= -14923) {
            return "N";
        }

        if ($charCode >= -14922 and $charCode <= -14915) {
            return "O";
        }

        if ($charCode >= -14914 and $charCode <= -14631) {
            return "P";
        }

        if ($charCode >= -14630 and $charCode <= -14150) {
            return "Q";
        }

        if ($charCode >= -14149 and $charCode <= -14091) {
            return "R";
        }

        if ($charCode >= -14090 and $charCode <= -13319) {
            return "S";
        }

        if ($charCode >= -13318 and $charCode <= -12839) {
            return "T";
        }

        if ($charCode >= -12838 and $charCode <= -12557) {
            return "W";
        }

        if ($charCode >= -12556 and $charCode <= -11848) {
            return "X";
        }

        if ($charCode >= -11847 and $charCode <= -11056) {
            return "Y";
        }

        if ($charCode >= -11055 and $charCode <= -10247) {
            return "Z";
        }

        return "?";
    }
}
