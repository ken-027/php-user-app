<?php

namespace Config;

class Cors
{
    public static function allowAll()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: *");
    }
}
