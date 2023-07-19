<?php

if (php_sapi_name() == 'cli-server') {
    define('REQUEST_PARAM', array_slice(explode('/', @$_SERVER['PATH_INFO']), 3, count(explode('/', @$_SERVER['PATH_INFO']))));
    define('REQUEST', $_SERVER['REQUEST_URI']);
    define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
    define('HTTP_HOST', isset($_SERVER['HTTPS']) ? "https://" : "http://" . $_SERVER['HTTP_HOST']);

    define('STORAGE', BASE_PATH . '/public/storage');
}

define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
