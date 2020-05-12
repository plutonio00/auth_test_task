<?php

namespace app\core;

use app\core\exception\ApplicationException;
use app\model\User;
use Exception;

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

    /**
     * @throws ApplicationException
     * @throws Exception
     */
    public function run()
    {
        $this->router = new Router();

        $controllerName = $this->router->getController();
        $methodName = $this->router->getAction();
        $isGuest = User::isGuest();

        if ($isGuest && User::hasCookie()) {
            User::login([
                'email' => $_COOKIE['email'],
                'password' => $_COOKIE['password'],
            ]);
        }

        $guestAccessGranted = $this->router->guestAccessGranted($controllerName, $methodName);

        if ($isGuest && !$guestAccessGranted) {
            $this->router->redirect('/auth/login');
            return;
        }

        if (!$isGuest && $guestAccessGranted) {
            $this->router->redirect('/');
            return;
        }

        if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
            $this->generateCsrfToken();
        }

        $class = sprintf('\\app\\controller\\%sController', ucfirst($controllerName));
        $method = 'action' . ucfirst($methodName);
        
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

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return Database
     * @throws ApplicationException
     */
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

    /**
     * @throws Exception
     */
    public function generateCsrfToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
