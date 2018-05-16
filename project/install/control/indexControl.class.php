<?php
class indexControl extends Control
{
    public function index()
    {
        self::display();
    }
    public function conf()
    {
        self::display();
    }
    public function init()
    {
        $sql = Conf::get('init.sql');
        if ($sql) {
            $sql = explode(';', $sql);
            foreach ($sql as $key => $value) {
                if (!empty(trim($value)) && Connect::exec($value) === false) {
                    die('init error');
                }
            }
            echo "success";
        } else {
            echo "file_error";
        }
    }
}
