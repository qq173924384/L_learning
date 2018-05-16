<?php
class MyString
{
    /**
     * 字符串加密解密
     * @param  string  $string      要加密解密的内容
     * @param  boolean $is_encode   是否是加密操作，默认解密
     * @param  integer $expiry      加密有效时间，单位秒
     * @param  string  $key         私钥
     * @param  integer $ckey_length 动态公钥长度
     * @return [type]               [description]
     */
    public static function authCode($string, $is_encode = false, $expiry = 0, $key = 'keypass', $ckey_length = 3)
    {
        if ($is_encode) {
            $keyc   = self::randString($ckey_length);
            $string = self::encodeTime($expiry ? ($expiry + time()) : 0) . $keyc . $string;
        } else {
            $string = base64_decode($string);
            $keyc   = substr($string, 0, $ckey_length);
            $string = substr($string, $ckey_length);
        }
        $string = self::sortCode($string, md5($keyc . $key));
        if ($is_encode) {
            return rtrim(base64_encode($keyc . $string), '=');
        } else {
            $time   = self::decodeTime(substr($string, 0, 5));
            $ckey   = substr($string, 5, $ckey_length);
            $string = substr($string, 5 + $ckey_length);
            if (($time == 0 || $time > time()) && $ckey == $keyc) {
                return $string;
            } else {
                return false;
            }
        }
    }
    /**
     * 随机字符串
     * @param  [type]  $length 随机字符串长度
     * @param  integer $type   随机范围类型，默认3，0：数字，1：数字+小写字母，2：数字+大小写字母
     * @return [type]          [description]
     */
    public static function randString($length, $type = 3)
    {
        $str = '';
        switch ($type) {
            case 0:
                $strPol = '0123456789';
                break;
            case 1:
                $strPol = '0123456789abcdefghijklmnopqrstuvwxyz';
                break;
            default:
                $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
                break;
        }
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;
    }
    /**
     * 字符串乱序加密解密
     * @param  string $string   加密解密文本
     * @param  string $cryptkey 密码
     * @return [type]           [description]
     */
    private static function sortCode($string, $cryptkey)
    {
        $string_length = strlen($string);
        $key_length    = strlen($cryptkey);
        $result        = '';
        $box           = range(0, $key_length - 1);
        $rndkey        = array();
        for ($i = 0; $i < $key_length; $i++) {
            $rndkey[$i] = ord($cryptkey[$i]);
        }
        for ($j = $i = 0; $i < $key_length; $i++) {
            $j       = ($j + $box[$i] + $rndkey[$i]) % $key_length;
            $tmp     = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a       = ($a + 1) % $key_length;
            $j       = ($j + $box[$a]) % $key_length;
            $tmp     = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % $key_length]));
        }
        return $result;
    }
    /**
     * 时间戳压缩
     * @param  int    $time [description]
     * @return [type]       [description]
     */
    private static function encodeTime($time)
    {
        $time = sprintf('%010d', $time);
        $str  = '';
        for ($i = 0; $i < 5; $i++) {
            $str .= chr($time[$i * 2] . $time[$i * 2 + 1]);
        }
        return $str;
    }
    /**
     * 时间戳解压
     * @param  string $time [description]
     * @return [type]       [description]
     */
    private static function decodeTime($str)
    {
        if (strlen($str) == 5) {
            $time = '';
            for ($i = 0; $i < 5; $i++) {
                $time .= ord($str[$i]);
            }
            return intval($time);
        } else {
            return false;
        }
    }
}
