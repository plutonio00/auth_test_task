<?php

namespace app\model;

use app\core\Application;

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

    public function registration(array $data)
    {

    }

    public static function login(array $credentials)
    {
        if (!empty($_SESSION['user'])) {
            return new User($_SESSION['user']);
        }
        else {
            $app = Application::instance();

            $sql = 'SELECT * FROM user WHERE email = ?';

            $user = $app->getDB()->customQuery($sql, 'select', [$credentials['email']])[0];

            if ($user) {
                if ($user['password'] === /*md5*/($credentials['password'])) {
                    $_SESSION['user'] = $user;

                    if (!empty($credentials['remember_me'])) {
                        setcookie('email', $user['email'], time() + self::COOKIE_TIME, '/');
                        setcookie('password', $user['password'], time() + self::COOKIE_TIME, '/');
                    }
                    return new User($user);
                }
                else {
                    return [
                        'password' => self::INCORRECT_PASSWORD,
                    ];
                }
            }
            else {
                return [
                    'email' => self::USER_NOT_FOUND,
                ];
            }
        }
    }

    public static function isGuest() {
        return empty($_SESSION['user']);
    }

    public static function hasCookie() {
        return !empty($_COOKIE['email']) && !empty($_COOKIE['password']);
    }
}
