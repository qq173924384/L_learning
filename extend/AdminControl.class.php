<?php
/**
 *
 */
class AdminControl extends AjaxControl
{
    public function __construct()
    {
        session_start();
    }
    protected function checkLogin()
    {
        if ($_SESSION['admin_id']) {
            return true;
        } else {
            if (strrchr(self::$action, 'Ajax') == 'Ajax') {
                self::returnErr('未登录', 401);
            } else {
                self::location(self::url('login-index'));
            }
        }
    }
    protected function getMethods($menu_title, $name = '')
    {
        $name    = $name ?: get_class($this);
        $methods = array_diff(get_class_methods($name . CONTROL_SUFFIX), get_class_methods(__CLASS__));

        $menu = [];
        if ($methods) {
            foreach ($methods as $value) {
                if (strrchr($value, 'Ajax') != 'Ajax') {
                    $key = $name . '-' . $value;

                    $menu[$value]['title']     = isset($menu_title[$key]['title']) ? $menu_title[$key]['title'] : $value;
                    $menu[$value]['target']    = isset($menu_title[$key]['target']) ? $menu_title[$key]['target'] : '_top';
                    $menu[$value]['href']      = self::url($name . '-' . $value);
                    $menu[$value]['is_active'] = false;
                    if ($value == self::$action && $name == (self::$control)) {
                        self::assign('title', $menu[$value]['title']);
                        $menu[$value]['is_active'] = true;
                    }
                }
            }
        }
        return $menu;
    }
    protected function getClass()
    {
        $nav_title  = Conf::get('admin_control');
        $menu_title = Conf::get('admin_method');
        $dir        = PROJECT_PATH . Control::$project . '/control/';
        $files_name = scandir($dir);
        $nav        = [];
        foreach ($files_name as $value) {
            if (strrchr($value, CONTROL_SUFFIX . CLASS_SUFFIX) == CONTROL_SUFFIX . CLASS_SUFFIX) {
                $class_name = str_replace(CLASS_SUFFIX, '', $value);
                $name       = str_replace(CONTROL_SUFFIX, '', $class_name);
                if (!in_array($name, ['login'])) {
                    require_once $dir . $value;
                    $nav[$name]['title']     = isset($nav_title[$name]['title']) ? $nav_title[$name]['title'] : $name;
                    $nav[$name]['icon']      = isset($nav_title[$name]['icon']) ? $nav_title[$name]['icon'] : 'fa fa-desktop';
                    $nav[$name]['menu']      = $this->getMethods($menu_title, $name);
                    $nav[$name]['is_active'] = false;
                    if ($name == self::$control) {
                        self::assign('title', $nav[$name]['title'] . '-' . self::assign('title'));
                        $nav[$name]['is_active'] = true;
                    }
                }
            }
        }
        return $nav;
    }
}
