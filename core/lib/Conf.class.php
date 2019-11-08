<?php
/**
 *
 */
class Conf
{
    private static $cache = [];

    /**
     * @param $file
     * @return mixed|string
     */
    protected static function getValue($file)
    {
        $type = ltrim(strrchr($file, '.'), '.');
        if (file_exists($file)) {
            switch ($type) {
                case 'json':
                    return json_decode(file_get_contents($file), 1);
                    break;
                case 'php':
                    return require $file;
                    break;
                default:
                    return file_get_contents($file);
                    break;
            }
        } else {
            Core::error($file . 'is not found');
            return false;
        }
    }
    protected static function setValue($data, $file)
    {
        $type = ltrim(strrchr($file, '.'), '.');
        if (file_exists($file)) {
            switch ($type) {
                case 'json':
                    return file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));
                    break;
                case 'php':
                    return file_put_contents($file, '<?php return ' . var_export($data, 1) . ';');
                    break;
                default:
                    return file_put_contents($file, $data);
                    break;
            }
        } else {
            Core::error($file . 'is not found');
            return false;
        }
    }
    public static function get($conf)
    {
        $conf = $conf . (strrchr($conf, '.') ? '' : CONF_SUFFIX);
        if (!isset(self::$cache[$conf])) {
            self::$cache[$conf] = self::getValue(CONF_PATH . $conf);
        }
        return self::$cache[$conf];
    }
    public static function set($conf, $data)
    {
        $conf = $conf . (strrchr($conf, '.') ? '' : CONF_SUFFIX);
        return self::setValue($data, CONF_PATH . $conf);
    }
}
