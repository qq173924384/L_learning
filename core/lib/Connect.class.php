<?php

/**
 *
 */
class Connect
{
    private static $connect = false, $is_debug = false;
    public static $prefix   = false;

    /**
     *
     */
    private static function _connect()
    {
        if (self::$connect) {
            return;
        }
        extension_loaded('PDO') or Core::error("PDO extension is unloaded");
        $conf     = Conf::get(DB_CONF);
        $type     = 'mysql';
        $host     = 'localhost';
        $dbname   = 'test';
        $port     = '3306';
        $username = 'root';
        $password = '';
        $charset  = 'UTF8mb4';
        if ($conf) {
            foreach ($conf as $key => $value) {
                switch ($key) {
                    case 'type':
                    case 'host':
                    case 'dbname':
                    case 'port':
                    case 'username':
                    case 'password':
                    case 'charset':
                        $$key = $value;
                        break;
                    case 'prefix':
                        self::$$key = $value;
                        break;
                    default:
                        break;
                }
            }
        }

        $dsn    = "$type:host=$host;dbname=$dbname;port=$port;charset=$charset;";
        $option = [
            PDO::ATTR_PERSISTENT        => true,
            PDO::ATTR_AUTOCOMMIT        => true,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_WARNING,
            PDO::ATTR_CASE              => PDO::CASE_NATURAL,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_TO_STRING,
        ];
        try {
            self::$connect = new PDO($dsn, $username, $password, $option);
        } catch (PDOException $e) {
            Core::error($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public static function beginTransaction()
    {
        self::_connect();
        self::$connect->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        return self::$connect->beginTransaction();
    }

    public static function rollback()
    {
        self::_connect();
        self::$connect->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        return self::$connect->rollback();
    }

    public static function commit()
    {
        self::_connect();
        self::$connect->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        return self::$connect->commit();
    }

    public static function inTransaction()
    {
        self::_connect();
        return self::$connect->inTransaction();
    }

    public static function lastInsertId()
    {
        self::_connect();
        return self::$connect->lastInsertId();
    }

    public static function exec($sql)
    {
        if (self::$is_debug) {
            return $sql;
        }
        self::_connect();
        $res = self::$connect->exec($sql);
        if ($res === false) {
            Core::error(self::$connect->errorInfo()[2]);
        } else {
            return $res;
        }
    }

    /**
     * @param $sql
     * @param bool $is_all
     * @param int $fech_style
     * @return mixed
     */
    public static function query($sql, $is_all = true, $fech_style = PDO::FETCH_ASSOC)
    {
        self::_connect();
        $query = self::$connect->query($sql);
        if ($query === false) {
            Core::error(self::$connect->errorInfo()[2]);
        } else {
            return $is_all ? $query->fetchAll($fech_style) : $query->fetch($fech_style);
        }
    }
    public static function debug($debug = true)
    {
        self::$is_debug = $debug;
    }
}
