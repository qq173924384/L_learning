<?php
class indexControl extends AdminControl
{
    public function index()
    {
        self::checkLogin();
        $this->assign('sidebar', $this->getClass());
        $this->layout();
    }
    public function setting()
    {
        var_dump('个人设置');
    }
    public function setMenu()
    {
        self::checkLogin();
        $this->assign('sidebar', $this->getClass());
        $this->layout();
    }
    public function setMenuAjax()
    {
        self::checkLogin();
        $key    = $_POST['key'];
        $type   = $_POST['type'];
        $value  = $_POST['value'];
        $extend = $_POST['extend'];
        self::checkParameter($key && $type && $value && $extend);
        switch ($type) {
            case 'control':
                $conf = 'admin_control';
                $data = Conf::get($conf);

                $data[$key]['icon'] = $extend;
                break;
            case 'method':
                $conf = 'admin_method';
                $data = Conf::get($conf);

                $data[$key]['target'] = $extend;
                break;
            default:
                self::returnErr('参数错误');
                break;
        }
        $data[$key]['title'] = $value;
        Conf::set($conf, $data);
        self::returnRes(1, '标题保存成功。');
    }
}
