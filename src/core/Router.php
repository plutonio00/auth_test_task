<?php

namespace app\core;

class Router
{
    private string $controller;
    private string $action;
    private static array $guestAccess = [
        'auth' => [
            'login', 'registration'
        ],
    ];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);

        $this->controller = !empty($urlParts[1]) ? $urlParts[1] : 'Default';
        $this->action = !empty($urlParts[2]) ? $urlParts[2] : 'index';
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $route
     */
    public function redirect(string $route): void {
        header(sprintf('Location: %s', $route));
    }

    /**
     * @param $controller
     * @param $method
     * @return bool
     */
    public function guestAccessGranted($controller, $method): bool
    {
        return !empty(self::$guestAccess[$controller])
            && in_array($method, self::$guestAccess[$controller], true);
    }
}
