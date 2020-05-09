<?php

namespace app\core;

use app\core\exception\ApplicationException;
use app\model\User;

class Application
{

    static private $instance = null;
    private $router;
    private $configuration = [];
    private $db = NULL;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function run()
    {

        $this->router = new Router();

        if (User::isGuest()) {
            $controllerName = 'Auth';
            $methodName = 'Login';
        } 
        else {
            $controllerName = ucfirst($this->router->getController());
            $methodName = ucfirst($this->router->getAction());
        }

        $class = sprintf('\\app\\controller\\%sController', $controllerName);
        $method = 'action' . $methodName;
        
        if (class_exists($class)) {
            $controller = new $class;

            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                throw new ApplicationException('Method ' . $class . ' not found', 503);
            }
        } else {
            throw new ApplicationException('Class ' . $class . ' not found', 502);
        }

    }

    public function setConfig(array $configuration)
    {
        if (empty($this->configuration)) {
            $this->configuration = $configuration;
        } else {
            throw new ApplicationException('Configuration has been already set up', 501);
        }
    }

    public function getConfig(string $parameterName)
    {
        $value = null;

        if (key_exists($parameterName, $this->configuration)) {
            $value = $this->configuration[$parameterName];
        } else {
            throw new ApplicationException('No config parameter found for key ' . $parameterName);
        }

        return $value;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getDB()
    {
        if ($this->db == NULL) {
            $this->db = new Database();
        }

        return $this->db;
    }

    /**
     * @return self
     */
    static public function instance()
    {
        return
            self::$instance === null ? self::$instance = new static() : self::$instance;
    }
}
