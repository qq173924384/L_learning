<?php
class loginControl extends AdminControl
{
    /**
     *
     */
    public function index()
    {
        if ($_POST) {
            if (intval($_SESSION['check_code']) != intval($_POST['check_code'])) {
                Core::error('验证码错误');
            }
            $mod = Model::build('admin');
            if ($admin = $mod->login($_POST['name'], $_POST['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin']    = $admin;
                self::location(self::url('index-index'));
            } else {
                Core::error('用户名或密码错误');
            }
        }
        $a = rand(1, 100);
        $b = rand(1, 20);
        if ($a < $b) {
            $c = $a;
            $a = $b;
            $b = $c;
        }
        $op = ['-', '+'];
        $op = $op[rand(0, 1)];
        switch ($op) {
            case '-':
                $_SESSION['check_code'] = $a - $b;
                break;
            case '+':
                $_SESSION['check_code'] = $a + $b;
                break;
        }
        self::assign('check_code', "$a $op $b");
        self::display();
    }
    public function logout()
    {
        session_destroy();
        self::location('/admin.html');
    }
}
