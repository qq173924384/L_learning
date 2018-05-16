<?php
class Control
{
    /**
     * 项目,控制器,方法
     * @project string
     * @control string
     * @action string
     */
    public static $project, $control, $action, $suffix, $site = '';
    /**
     * [$assign_data description]
     * @var array
     */
    protected static $assign_data = [], $layout_string;

    public static function run()
    {
        // 解析路由
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        if (self::$suffix = strrchr($path_info, '.')) {
            $path_info = substr($path_info, 0, strlen($path_info) - strlen(self::$suffix));
        }
        $path_info = explode('/', trim($path_info, '/'));
        $file_path = self::route($path_info[0]);

        $GLOBALS['site'] = '';
        if (!file_exists($file_path)) {
            self::$site = $path_info[0];
            $path_info  = array_slice($path_info, 1);
            $file_path  = self::route(isset($path_info[0]) ? $path_info[0] : '');
        }
        if (self::$action == 'run') {
            Core::error('action is error!');
        }
        if (!file_exists($file_path)) {
            Core::error($file_path . ' is not found!');
        }
        if (sizeof($path_info) > 1) {
            $arg_array = array_slice($path_info, 1);
            for ($i = 0; $i < sizeof($arg_array); $i += 2) {
                if (isset($arg_array[$i + 1])) {
                    $_GET[$arg_array[$i]] = $arg_array[$i + 1];
                }
            }
        }

        // 运行控制器
        $class_name = self::$control . CONTROL_SUFFIX;
        require_once $file_path;
        try {
            $class  = new ReflectionClass($class_name);
            $method = new ReflectionMethod($class_name, self::$action);
        } catch (Exception $e) {
            Core::error($e->getMessage());
        }
        if ($method->isPublic()) {
            $obj = new $class_name();
            $fun = self::$action;
            $obj->$fun();
        } else {
            Core::error("Method {$method->class}::{$method->name}() is not public");
        }
    }

    /**
     * @param $path_info
     * @return string
     */
    protected static function route($path_info)
    {
        self::$project = DEFAULT_PRO;
        self::$control = DEFAULT_CON;
        self::$action  = DEFAULT_ACT;

        $path_info = explode('-', $path_info);
        switch (sizeof($path_info)) {
            case 3:
                $path_info[2] and self::$action = $path_info[2];
            case 2:
                $path_info[1] and self::$control = $path_info[1];
            case 1:
                $path_info[0] and self::$project = $path_info[0];
            default:
                break;
        }
        return PROJECT_PATH . self::$project . '/control/' . self::$control . CONTROL_SUFFIX . CLASS_SUFFIX;
    }
    protected static function viewPath($string = '')
    {
        $project = self::$project;
        $control = self::$control;
        $action  = self::$action;
        if ($string) {
            $string = array_reverse(explode('-', $string), 0);
            switch (sizeof($string)) {
                case 3:
                    $project = $string[2];
                case 2:
                    $control = $string[1];
                case 1:
                    $action = $string[0];
                    break;
                default:
                    Core::error('viewPath string is error!');
                    break;
            }
        }
        $file_path = PROJECT_PATH . $project . '/view/' . $control . '/' . $action . VIEW_SUFFIX;
        if (file_exists($file_path)) {
            return $file_path;
        } else {
            Core::error($file_path . ' is not found!');
        }
    }

    /**
     * 获取路径
     * @param  string $string [description]
     * @param  array $get [description]
     * @return string [type]           [description]
     */
    protected static function url($string = '', array $get = [])
    {
        $url     = 'http://' . HTTP_HOST;
        $project = self::$project;
        $control = self::$control;
        $action  = self::$action;
        if ($suffix = strrchr($string, '.')) {
            $string = substr($string, 0, strlen($string) - strlen($suffix));
        }
        if ($string) {
            $string = array_reverse(explode('-', $string), 0);
            switch (sizeof($string)) {
                case 3:
                    $project = $string[2];
                case 2:
                    $control = $string[1];
                case 1:
                    $action = $string[0];
                    break;
                default:
                    Core::error('viewPath string is error!');
                    break;
            }
        }
        $url .= (self::$site ? ('/' . self::$site) : '') . '/' . $project;
        if ($action != DEFAULT_ACT) {
            $url .= '-' . $control . '-' . $action;
        } elseif ($control != DEFAULT_CON) {
            $url .= '-' . $control;
        }
        if ($get) {
            foreach ($get as $key => $value) {
                $url .= '/' . $key . '/' . $value;
            }
        }
        $url .= $suffix ?: ROUTE_SUFFIX;
        return $url;
    }
    protected static function assign($string, $data = null)
    {
        if ($data === null) {
            return self::$assign_data[$string];
        } else {
            self::$assign_data[$string] = $data;
        }
    }
    protected static function display($string = '')
    {
        foreach (self::$assign_data as $key => $value) {
            $$key = $value;
        }
        require self::viewPath($string);
    }
    protected static function layout($layout_string = '')
    {
        self::$layout_string = $layout_string;
        unset($layout_string);
        extract(self::$assign_data);
        require PROJECT_PATH . self::$project . '/view/layout.php';
    }
    protected static function location($url)
    {
        header('location:' . $url);
        die;
    }
    protected static function str_trim($str)
    {
        $str = preg_replace("@<script(.*?)</script>@is", "", $str);
        $str = preg_replace("@<iframe(.*?)</iframe>@is", "", $str);
        $str = preg_replace("@<style(.*?)</style>@is", "", $str);
        return preg_replace("@<(.*?)>@is", "", $str);
    }
}
