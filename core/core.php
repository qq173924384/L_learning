<?php
define('CORE_PATH', __DIR__ . '/');
define('LIB_PATH', CORE_PATH . 'lib/');
// require CORE_PATH . 'define.php';
defined('DEBUG') or define('DEBUG', 0);
defined('DB_CONF') or define('DB_CONF', 'localdb');

defined('CONF_PATH') or define('CONF_PATH', ROOT_PATH . 'conf/');
defined('CACHE_PATH') or define('CACHE_PATH', ROOT_PATH . 'cache/');
defined('MODEL_PATH') or define('MODEL_PATH', ROOT_PATH . 'model/');
defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend/');
defined('PROJECT_PATH') or define('PROJECT_PATH', ROOT_PATH . 'project/');

defined('DEFAULT_PRO') or define('DEFAULT_PRO', 'index');
defined('DEFAULT_CON') or define('DEFAULT_CON', 'index');
defined('DEFAULT_ACT') or define('DEFAULT_ACT', 'index');

defined('CLASS_SUFFIX') or define('CLASS_SUFFIX', '.class.php');
defined('MODEL_SUFFIX') or define('MODEL_SUFFIX', 'Model');
defined('CONTROL_SUFFIX') or define('CONTROL_SUFFIX', 'Control');
defined('CONF_SUFFIX') or define('CONF_SUFFIX', '.json');
defined('VIEW_SUFFIX') or define('VIEW_SUFFIX', '.php');
defined('ROUTE_SUFFIX') or define('ROUTE_SUFFIX', '.html');

defined('HTTP_HOST') or define('HTTP_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', CORE_PATH . 'tmp/logs/error.log');
}
ini_set('date.timezone','Asia/Shanghai');
/**
 * @param string $class_name 核心类库、拓展类库自动加载方法
 */
function coreAutoLoad($class_name)
{
    $class_name = str_replace('\\', '/', trim($class_name, '\\'));
    $files      = [
        LIB_PATH . $class_name . CLASS_SUFFIX,
        EXTEND_PATH . $class_name . CLASS_SUFFIX,
        MODEL_PATH . $class_name . CLASS_SUFFIX,
    ];
    foreach ($files as $file) {
        if (file_exists($file)) {
            return require $file;
        }
    }
    return false;
}
spl_autoload_register('coreAutoLoad');
Control::run();
