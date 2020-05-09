<?php

namespace app\model;

use app\core\Application;

class User
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $createdAt;
    const INCORRECT_PASSWORD = 'Incorrect password';
    const USER_NOT_FOUND = 'User with such email not found';

    /**
     * @param $id
     */
    public function __construct(int $id)
    {
        if ($id > 0) {
            $this->findById($id);
        }
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

    public function findById(int $id): void
    {
        $app = Application::instance();
        $sql = 'SELECT * FROM users WHERE id = ?';
        $result = $app->getDB()->queryGet($sql, [$id]);

        if (isset($result[0])) {
            $this->id = $result[0]['id'];
            $this->firstName = $result[0]['first_name'];
            $this->lastName = $result[0]['last_name'];
            $this->email = $result[0]['email'];
        }
    }

    public function registration(array $data)
    {
    }

    public static function login(array $credentials)
    {
        if (isset($_SESSION['userId'])) {
            return new User($_SESSION['userId']);
        }
//        elseif (isset($_COOKIE['userId'])) {
//
//        }
        else {
            $app = Application::instance();

            $sql = 'SELECT * FROM user WHERE email = ?';

            $user = $app->getDB()->queryGet($sql, [$credentials['email']])[0];

            if ($user) {
                if ($user['password'] === /*md5*/($credentials['password'])) {
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['password'] = $user['password'];
                    $_SESSION['userId'] = $user['id'];
                    if (isset($credentials['remember_me'])) {
                        setcookie($user['email'], $user['password'], 3600);
                    }
                    return new User($user[0]['id']);
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
        return empty($_SESSION['userId']);
    }
}
