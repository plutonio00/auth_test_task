<?php

namespace app\core;

class Router
{
    private $controller = '';
    private $action = '';

    public function __construct()
    {
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);

        $this->controller = !empty($urlParts[1]) ? $urlParts[1] : 'Default';
        $this->action = !empty($urlParts[2]) ? $urlParts[2] : 'index';
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function redirect(string $route) {
        header(sprintf('Location: %s', $route));
    }
}
