<?php
class LtcConfig
{

}
/**
 *
 */
class CosUtil
{

    const OAUTH_TOKEN = '';
    // cos配置
    const COS_BUCKET     = '';
    const COS_REGION     = 'ap-guangzhou';
    const COS_APPID      = '';
    const COS_SECRET_ID  = '';
    const COS_SECRET_KEY = '';
    const FMS_APPID      = '';
    const CONTRACT_PATH  = '';

    public static $ROLE_ARR = array(
        'businessManager',
        'architect',
        'productManager',
        'serviceGuarantee',
        'other',
    );

    public static $ROLE_LIST = array(
        array("text" => "商务经理", "roleName" => "businessManager"),
        array("text" => "架构师", "roleName" => "architect"),
        array("text" => "售中/产品经理", "roleName" => "productManager"),
        array("text" => "售后/服务保障", "roleName" => "serviceGuarantee"),
        array("text" => "其他", "roleName" => "other"),
    );

    public static function getClient()
    {
        get_option('cos_options', true);
        return new Qcloud\Cos\Client([
            'region'      => LtcConfig::COS_REGION,
            'credentials' => [
                'appId'     => LtcConfig::COS_APPID,
                'secretId'  => LtcConfig::COS_SECRET_ID,
                'secretKey' => LtcConfig::COS_SECRET_KEY,
            ],
        ]);
    }
    public static function upload($file, $name)
    {
        $date   = date('Ym');
        $client = CosUtil::getClient();
        return $client->Upload(LtcConfig::COS_BUCKET, LtcConfig::CONTRACT_PATH . $name, fopen($file, 'rb'));
    }
    public static function getObjectUrl($name)
    {
        return self::getUrl(LtcConfig::CONTRACT_PATH . $name);
    }
    public static function getDownloadUrl($url)
    {
        $url = ltrim(ltrim($url, 'http:'), '//');
        $url = substr($url, strpos($url, '/') + 1);
        return self::getUrl($url);
    }
    public static function getUrl($key)
    {
        $client = CosUtil::getClient();
        $url    = $client->getObjectUrl(LtcConfig::COS_BUCKET, $key, '+10 minutes');
        $url    = explode('?', $url);
        $url[0] = urldecode($url[0]);
        return implode('?', $url);
    }
}
