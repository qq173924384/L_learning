<?php

/**
 * Created by PhpStorm.
 * User: L
 * Date: 2016/11/29
 * Time: 10:03
 */
class indexControl extends Control
{
    public function index()
    {
        $model = Model::build('user');
        $where = ['id' => 100017];
        $model->update(['nick_name' => '龙😂😘😍😀😥'], $where);
        var_dump($model->field('nick_name')->select($where));
    }
}
