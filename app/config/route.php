<?php

namespace Config;

use Lib\Response;
use ReflectionMethod;

class Route
{
    public static function get(string $route, array $controller, bool $exceptApi = false)
    {
        if (self::isApi() || $exceptApi) {
            if (REQUEST_METHOD === 'GET') {
                if ($route === REQUEST || self::isParamId()) {
                    $class = array_key_first($controller);
                    $reflechMethod = new ReflectionMethod($class, $controller[$class]);
                    $reflechMethod->invoke(new $class());
                }
            }
            // self::errorEndpoint();
        }
    }

    public static function post(string $route, array $controller)
    {
        if (self::isApi()) {
            if (REQUEST_METHOD === 'POST') {
                if ($route === REQUEST || self::isParamId()) {
                    $class = array_key_first($controller);
                    $reflechMethod = new ReflectionMethod($class, $controller[$class]);
                    $reflechMethod->invoke(new $class());
                }
            }
            // self::errorEndpoint();
        }
    }

    public static function delete(string $route, array $controller)
    {
        if (self::isApi()) {
            if (REQUEST_METHOD === 'DELETE') {
                if ($route === REQUEST || self::isParamId()) {
                    $class = array_key_first($controller);
                    $reflechMethod = new ReflectionMethod($class, $controller[$class]);
                    $reflechMethod->invoke(new $class());
                }
            }
            // self::errorEndpoint();
        }
    }

    private static function errorEndpoint()
    {
        Response::json(["message" => sprintf("Method `%s` for endpoint %s not found!", REQUEST_METHOD, REQUEST)]);
    }

    public static function staticFiles()
    {
        if (preg_match("/.(?:png|jpg|jpeg|gif)$/", REQUEST)) {
            exit;
        }
    }

    private static function isApi(): bool
    {
        return (bool) preg_match("/(\/api(\/*))/m", REQUEST);
    }

    private static function isParamId(): bool
    {
        return (bool) preg_match("/(\/api\/users\/[\d]+(\/*))$/m", REQUEST);
    }
}
