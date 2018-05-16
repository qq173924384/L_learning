<?php
class indexControl extends Control
{
    public function index()
    {
        $mod  = Model::build('cate');
        $time = microtime(1);
        for ($i = 0; $i < 1; $i++) {
            $data = $mod->getTreeByPid(1, 0);
        }
        $time = microtime(1) - $time;
        echo "<pre>";
        var_dump($time);
        var_dump($data);
        die;
        self::display();
    }
}
