<?php

namespace app\model;

use app\core\Application;
use app\core\exception\ApplicationException;
use app\helper\FileLoaderHelper;
use DateTime;
use Exception;
use PDO;

class User
{
    private string $id;
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $password;
    private string $avatar;
    private string $createdAt;
    private const INCORRECT_PASSWORD = 'Incorrect password';
    private const USER_NOT_FOUND = 'User with such email not found';
    private const ALREADY_REGISTERED = 'User with such email already registered';
    private const COOKIE_TIME = 24 * 3600;
    private const FILE_LOAD_ERROR = 'File load error. Please, reload the page';
    private const DEFAULT_AVATAR = 'default.png';
    private const IMAGE_PATH = 'images/';
    private const DATABASE_ERROR = 'Error with database. Try again';


    /**
     * User constructor.
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
        $this->avatar = $data['avatar'] ?? self::DEFAULT_AVATAR;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param array $credentials
     * @return User|array
     * @throws ApplicationException
     */
    public static function registration(array $credentials)
    {
        $app = Application::instance();

        if (self::findByEmail($credentials['email'])) {
            return [
                'email' => self::ALREADY_REGISTERED,
            ];
        }

        $salt = uniqid('', true);
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

        $userRaw = self::findByEmail($credentials['email']);

        if ($userRaw) {
            $user = new User($userRaw);
            $_SESSION['user'] = $user;
            Application::instance()->generateCsrfToken();
            return $user;
        }

        return [
            'common' => self::DATABASE_ERROR
        ];
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @param array $credentials
     * @return User|array
     * @throws Exception
     */
    public static function login(array $credentials)
    {
        $userData = self::findByEmail($credentials['email']);
        $hashPass = md5(md5($credentials['password'] . $userData['salt']));

        if ($userData) {
            if ($userData['password'] === $hashPass) {

                $user = new User($userData);
                $_SESSION['user'] = $user;

                if ($credentials['remember_me']) {
                    setcookie('email', $user->getEmail(), time() + self::COOKIE_TIME, '/');
                    setcookie('password', $user->getPassword(), time() + self::COOKIE_TIME, '/');
                }
                Application::instance()->generateCsrfToken();

                return $user;
            }

            return [
                'password' => self::INCORRECT_PASSWORD,
            ];
        }

        return [
            'email' => self::USER_NOT_FOUND,
        ];
    }

    /**
     * @return bool
     */
    public static function isGuest(): bool
    {
        return empty($_SESSION['user']);
    }

    /**
     * @return bool
     */
    public static function hasCookie(): bool
    {
        return !empty($_COOKIE['email']) && !empty($_COOKIE['password']);
    }

    /**
     * @param string $email
     * @return mixed
     */
    public static function findByEmail(string $email)
    {
        $app = Application::instance();
        $db = $app->getDB();

        return $db
            ->select('user')
            ->where('email')
            ->exec([$email])
            ->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    /**
     * @return string
     */
    public function getAvatarFullPath(): string
    {
        return self::IMAGE_PATH . $this->avatar;
    }
}
