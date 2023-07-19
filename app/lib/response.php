<?php

namespace Lib;

class Response
{
    public static function json(string | array | object | null $data = null, int $code = 200): void
    {
        header('content-type: application/json');

        http_response_code($code);

        if ($data !== null) {
            echo json_encode($data);
        } else {
            http_response_code(204);
        }
        exit;
    }
}
