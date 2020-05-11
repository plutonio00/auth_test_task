<?php

namespace app\model;

use app\core\Application;
use app\helper\FileLoaderHelper;
use DateTime;
use PDO;

class User
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $password;
    private $createdAt;
    const INCORRECT_PASSWORD = 'Incorrect password';
    const USER_NOT_FOUND = 'User with such email not found';
    const ALREADY_REGISTERED = 'User with such email already registered';
    const COOKIE_TIME = 24 * 3600;
    const FILE_LOAD_ERROR = 'File load error. Please, reload the page';

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->firstName = $data['first_name'];
        $this->lastName = $data['last_name'];
        $this->password = $data['password'];
        $this->createdAt = $data['created_at'];
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public static function registration(array $credentials)
    {
        $app = Application::instance();

        if (self::findByEmail($credentials['email'])) {
            return [
                'email' => self::ALREADY_REGISTERED,
            ];
        } else {
            $salt = uniqid();
            $credentials['password'] = md5(md5($credentials['password'] . $salt));
            $credentials['salt'] = $salt;

            if (!empty($credentials['avatar'])) {
                $credentials['avatar'] = FileLoaderHelper::downloadFile($credentials['avatar']);

                if (!$credentials['avatar']) {
                    return [
                        'avatar' => self::FILE_LOAD_ERROR,
                    ];
                }
            } else {
                unset($credentials['avatar']);
            }

            $db = $app->getDB();
            $db
                ->insert('user', array_keys($credentials))
                ->exec(array_values($credentials));


            $_SESSION['user'] = $credentials;

            Application::instance()->generateCsrfToken();

            return true;
        }
    }

    public static function login(array $credentials)
    {
        $user = self::findByEmail($credentials['email']);
        $hashPass = md5(md5($credentials['password'] . $user['salt']));

        if ($user) {
            if ($user['password'] === $hashPass) {
                $_SESSION['user'] = [
                    'email' => $credentials['email'],
                    'password' => $hashPass,
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'avatar' => $user['avatar']
                ];

                if (isset($credentials['remember_me'])) {
                    setcookie('email', $user['email'], time() + self::COOKIE_TIME, '/');
                    setcookie('password', $user['password'], time() + self::COOKIE_TIME, '/');
                }
                Application::instance()->generateCsrfToken();
                return new User($user);
            } else {
                return [
                    'password' => self::INCORRECT_PASSWORD,
                ];
            }
        } else {
            return [
                'email' => self::USER_NOT_FOUND,
            ];
        }
    }

    public static function isGuest()
    {
        return empty($_SESSION['user']);
    }

    public static function hasCookie()
    {
        return !empty($_COOKIE['email']) && !empty($_COOKIE['password']);
    }

    public static function findByEmail(string $email)
    {
        $app = Application::instance();
        $db = $app->getDB();

        return $db
            ->select('user')
            ->where('email')
            ->exec([$email])
            ->fetch(PDO::FETCH_ASSOC);;
    }
}
