<?php
/**
 *
 */
class Cache
{
    public static function get($key)
    {
        $file = CACHE_PATH . $key . '.cache';
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), 1);
        } else {
            return false;
        }
    }
    public static function put($key, $data)
    {
        $file = CACHE_PATH . $key . '.cache';
        return file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
