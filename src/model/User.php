<?php

namespace app\model;

use app\core\Application;
use app\core\exception\ApplicationException;
use app\helper\FileLoaderHelper;
use app\helper\SecurityHelper;
use Exception;
use PDO;

class User
{
    private string $id;
    private string $email;
    private string $first_name;
    private string $last_name;
    private string $password;
    private string $avatar;
    private string $created_at;
    private string $auth_key;
    private const INCORRECT_PASSWORD = 'Incorrect password';
    private const USER_NOT_FOUND = 'User with such email not found';
    private const ALREADY_REGISTERED = 'User with such email already registered';
    private const COOKIE_TIME = 24 * 3600;
    private const FILE_LOAD_ERROR = 'File load error. Please, reload the page';
    private const DEFAULT_AVATAR = 'default.png';
    private const IMAGE_PATH = '/images/';
    private const DATABASE_ERROR = 'Error with database. Try again';
    private const TABLE_NAME = 'user';

    /**
     * User constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->password = $data['password'];
        $this->created_at = $data['created_at'];
        $this->avatar = $data['avatar'] ?? self::DEFAULT_AVATAR;
        $this->auth_key = $data['auth_key'] ?? '';
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
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
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
     * @return mixed|string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param mixed|string $auth_key
     */
    public function setAuthKey(string $auth_key): void
    {
        $this->auth_key = $auth_key;
    }

    /**
     * @param array $credentials
     * @return User|array
     * @throws ApplicationException
     */
    public static function registration(array $credentials)
    {
        if (self::findByField('email', $credentials['email'])) {
            return [
                'email' => self::ALREADY_REGISTERED,
            ];
        }

        $salt = SecurityHelper::generateRandomString();
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

        $app = Application::instance();
        $db = $app->getDB();
        $db
            ->insert('user', array_keys($credentials))
            ->exec(array_values($credentials));

        $userRaw = self::findByField('email', $credentials['email']);

        if ($userRaw) {
            $user = new User($userRaw);
            $_SESSION['user'] = $user->getDataForSession();
            Application::instance()->generateCsrfToken();
            return $user;
        }

        return [
            'common' => self::DATABASE_ERROR
        ];
    }

    /**
     * @param array $credentials
     * @return User|array
     * @throws Exception
     */
    public static function login(array $credentials)
    {
        $userData = self::findByField('email', $credentials['email']);
        $hashPass = md5(md5($credentials['password'] . $userData['salt']));

        if ($userData) {
            if ($userData['password'] === $hashPass) {

                $user = new User($userData);
                $_SESSION['user'] = $user->getDataForSession();

                if ($credentials['remember_me']) {
                    $authKey = SecurityHelper::generateRandomString();
                    setcookie('auth_key', $authKey, time() + self::COOKIE_TIME, '/', '', false, true);
                    $user->auth_key = $authKey;
                    $user->updateInDb(['auth_key']);
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
    public static function hasAuthCookie(): bool
    {
        return !empty($_COOKIE['auth_key']);
    }

    public static function findByField(string $fieldName, $value, $columns = '*')
    {
        $app = Application::instance();
        $db = $app->getDB();

        return $db
            ->select(static::TABLE_NAME, $columns)
            ->where($fieldName)
            ->exec([$value])
            ->fetch(PDO::FETCH_ASSOC);
    }

    public function updateInDb(array $fields)
    {
        $app = Application::instance();
        $db = $app->getDB();

        $values = [];
        foreach ($fields as $field) {
            $values[] = $this->{$field};
        }

        $values[] = $this->id;

        return $db
            ->update(self::TABLE_NAME, $fields)
            ->where('id')
            ->exec($values);
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    /**
     * @return string
     */
    public function getAvatarFullPath(): string
    {
        return self::IMAGE_PATH . $this->avatar;
    }

    private function getDataForSession(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }
}
