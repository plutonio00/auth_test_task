<?php

namespace app\core;

use app\core\exception\ApplicationException;
use app\model\User;
use Exception;

class Application
{

    static private $instance;
    private Router $router;
    private array $configuration = [];
    private Database $db;

    /**
     * Application constructor.
     */
    private function __construct()
    {
    }

    private function __clone() {}

    private function __wakeup() {}

    /**
     * @throws ApplicationException
     * @throws Exception
     */
    public function run(): void
    {
        $this->router = new Router();

        $controllerName = $this->router->getController();
        $methodName = $this->router->getAction();
        $isGuest = User::isGuest();

        if ($isGuest && User::hasAuthCookie()) {
            /** @var User $user */
            $userData = User::findByField('auth_key', $_COOKIE['auth_key']);

            if (!$userData) {
                $this->router->redirect('/auth/login');
            }

            User::login([
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);
        }

        $guestAccessGranted = $this->router->guestAccessGranted($controllerName, $methodName);

        if ($isGuest && !$guestAccessGranted) {
            $this->router->redirect('/auth/login');
            return;
        }

        if (!$isGuest && $guestAccessGranted) {
            $this->router->redirect('/user/profile');
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

    /**
     * @param array $configuration
     * @throws ApplicationException
     */
    public function setConfig(array $configuration): void
    {
        if (empty($this->configuration)) {
            $this->configuration = $configuration;
        } else {
            throw new ApplicationException('Configuration has been already set up', 501);
        }
    }

    /**
     * @param string $parameterName
     * @return mixed
     * @throws ApplicationException
     */
    public function getConfig(string $parameterName)
    {
        $value = null;

        if (array_key_exists($parameterName, $this->configuration)) {
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
     */
    public function getDB(): Database
    {
        if (!isset($this->db)) {
            $this->db = new Database();
        }

        return $this->db;
    }

    /**
     * @return self
     */
    public static function instance(): self
    {
        return self::$instance ?? (self::$instance = new static());
    }

    /**
     * @throws Exception
     */
    public function generateCsrfToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
