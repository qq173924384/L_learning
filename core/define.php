<?php
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
