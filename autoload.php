<?php

define("BASE_PATH", __DIR__);
include_once __DIR__ . "/app/config/const.php";


spl_autoload_register(function ($className) {
    // require __DIR__ . "/app/" . $className . ".php";
    $path_to_file = __DIR__ . '/app/' . $className . '.php';

    if (file_exists($path_to_file)) {
        include $path_to_file;
    }
});
