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
    private $avatar;
    const INCORRECT_PASSWORD = 'Incorrect password';
    const USER_NOT_FOUND = 'User with such email not found';
    const ALREADY_REGISTERED = 'User with such email already registered';
    const COOKIE_TIME = 24 * 3600;
    const FILE_LOAD_ERROR = 'File load error. Please, reload the page';
    const DEFAULT_AVATAR = 'default.png';
    const IMAGE_PATH = 'images/';
    const DATABASE_ERROR = 'Error with database. Try again';

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

        if ($data['avatar']) {
            $this->avatar = $data['avatar'];
        }
        else {
            $this->avatar = self::DEFAULT_AVATAR;
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

            $idUser = $db->getLastId();

            if (is_numeric($idUser) && $idUser > 0) {
                $credentials['id'] = $idUser;
                $_SESSION['user'] = new User($credentials);
                Application::instance()->generateCsrfToken();
                return $idUser;
            }
            else {
                return [
                    'common' => self::DATABASE_ERROR
                ];
            }
        }
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public static function login(array $credentials)
    {
        $userData = self::findByEmail($credentials['email']);
        $hashPass = md5(md5($credentials['password'] . $userData['salt']));

        if ($userData) {
            if ($userData['password'] === $hashPass) {

                $_SESSION['user'] = new User([
                    'email' => $credentials['email'],
                    'password' => $hashPass,
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'avatar' => $userData['avatar']
                ]);


                if (isset($credentials['remember_me'])) {
                    setcookie('email', $userData['email'], time() + self::COOKIE_TIME, '/');
                    setcookie('password', $userData['password'], time() + self::COOKIE_TIME, '/');
                }
                Application::instance()->generateCsrfToken();
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

    public function getFullName() {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getAvatarFullPath() {
        return self::IMAGE_PATH . $this->avatar;
    }
}
