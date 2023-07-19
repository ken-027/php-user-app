<?php

namespace Lib;

class Connection
{
    protected static $conn = null;

    public function __construct()
    {
        try {
            if (self::$conn === null) {
                self::$conn = new \mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            }
        } catch (\Exception $e) {
            Response::json(["error" => $e->getMessage()]);
        }
    }

    public function get(): ?object
    {
        return self::$conn;
    }

    public function __destruct()
    {
        if (self::$conn !== null) {
            self::$conn->close();
            self::$conn = null;
        }
    }
}
