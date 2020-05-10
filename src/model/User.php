<?php

namespace app\model;

use app\core\Application;
use DateTime;

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
        }
        else {
            $salt = uniqid();
            $hashPass = md5(md5($credentials['password'] . $salt));

            $sql = 'INSERT INTO user (email, password, salt, first_name, last_name) VALUES (?, ?, ?, ?, ?)';

            $userId = $app->getDB()->customQuery($sql, 'insert', [
                $credentials['email'],
                $hashPass,
                $salt,
                $credentials['first_name'],
                $credentials['last_name'],
            ]);

            if (is_numeric($userId) && $userId > 0) {
                $_SESSION['user'] = [
                    $credentials['email'],
                    $hashPass,
                    $credentials['first_name'],
                    $credentials['last_name'],
                ];
                Application::instance()->generateCsrfToken();

                return $userId;
            }
        }
    }

    public static function login(array $credentials)
    {
        if (!empty($_SESSION['user'])) {
            return new User($_SESSION['user']);
        } else {

            $user = self::findByEmail($credentials['email']);
            $hashPass = md5(md5($credentials['password'] . $user['salt']));

            if ($user) {
                if ($user['password'] === $hashPass) {
                    $_SESSION['user'] = [
                        $credentials['email'],
                        $hashPass,
                        $user['first_name'],
                        $user['last_name'],
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

        $sql = 'SELECT * FROM user WHERE email = ?';
        return $app->getDB()->customQuery($sql, 'select', [$email])[0];
    }
}
