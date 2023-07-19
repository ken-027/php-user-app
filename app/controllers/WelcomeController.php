<?php

namespace Controllers;

use Lib\Response;

class WelcomeController
{
    public function index(): void
    {
        Response::json(["message" => "Welcome to the vanilla php api"]);
    }
}
