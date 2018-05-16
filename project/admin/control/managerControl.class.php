<?php
class managerControl extends AdminControl
{
    public function index()
    {
        self::checkLogin();
        self::assign('sidebar', self::getClass());

        $mod = Model::build('admin');
        self::assign('admin', $mod->select());

        $mod = Model::build('role');
        self::assign('role', $mod->select());

        $mod = Model::build('site');
        self::assign('site', $mod->select());

        self::layout();
    }
    public function indexAjax()
    {
        self::checkLogin();
        if (isset($_POST['delete']) && $_POST['id']) {
            $mod = Model::build('admin');
            self::returnRes($mod->delete(['id' => intval($_POST['id'])]), '删除成功');
        }
        $login   = self::str_trim($_POST['login']);
        $site_id = intval($_POST['site_id']);
        $role_id = intval($_POST['role_id']);
        self::checkParameter($login && $site_id && $role_id);
        $mod  = Model::build('admin');
        $data = ['login' => $login, 'site_id' => $site_id, 'role_id' => $role_id];
        if ($id = intval($_POST['id'])) {
            self::returnRes($mod->update($data, ['id' => $id]), '修改成功');
        } else {
            if ($mod->field('id')->selectOne(['login' => $login])) {
                self::returnErr('管理员已存在');
            }
            $data['password'] = '21232f297a57a5a743894a0e4a801fc3';
            self::returnRes($mod->insert($data), '添加成功!默认密码"admin"');
        }
    }
    public function role()
    {
        self::checkLogin();
        self::assign('sidebar', self::getClass());

        $mod = Model::build('role');
        self::assign('role', $mod->select());

        self::layout();
    }
    public function roleAjax()
    {
        self::checkLogin();
        if (isset($_POST['delete']) && $_POST['id']) {
            $mod = Model::build('role');
            self::returnRes($mod->delete(['id' => intval($_POST['id'])]), '删除成功');
        }
        $name   = self::str_trim($_POST['name']);
        $rights = self::str_trim($_POST['rights']);
        self::checkParameter($name && $rights);
        $mod  = Model::build('role');
        $data = ['name' => $name, 'rights' => json_encode($rights)];
        if ($id = intval($_POST['id'])) {
            self::returnRes($mod->update($data, ['id' => $id]), '修改成功');
        } else {
            self::returnRes($mod->insert($data), '添加成功');
        }
    }
}
