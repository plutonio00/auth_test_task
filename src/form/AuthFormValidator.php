<?php

namespace app\form;

class AuthFormValidator
{
    private const EMPTY_VALUE = 'This field needs to be filled';
    private const MINIMAL_PASSWORD_LENGTH = 6;
    private const SHORT_PASSWORD = 'Password is too short. Type at least ' . self::MINIMAL_PASSWORD_LENGTH . ' symbols';
    private const AGREE_TERMS = 'You should agree with these terms';
    private const INVALID_EMAIL = 'This email is incorrect';
    private const INVALID_CSRF_TOKEN = 'Invalid csrf token';
    private const SIMPLE_MESSAGE_ERROR_FOR_USER = 'Error. Please, reload the page';
    private const IMG_TYPES = [
        'image/jpg', 'image/jpeg', 'image/png', 'image/bpm',
    ];
    private const INVALID_FILE_TYPE = 'Invalid file type';
    private const TOO_BIG_FILE = 'Please, load a file no larger than 30MB';
    private const MAX_FILE_SIZE_IN_BYTE = 30 * 1024 * 1024;

    private array $errors;

    /**
     * AuthFormValidator constructor.
     */
    public function __construct()
    {
        $this->errors = [];
    }

    /**
     * @param array $values
     */
    protected function valuesIsEmpty(array $values): void
    {
        foreach ($values as $key => $value) {
            if (empty($value)) {
                $this->errors[$key] = self::EMPTY_VALUE;
            }
        }
    }

    /**
     * @param string $password
     */
    protected function validatePassword(string $password): void
    {
        if (!isset($this->errors['password']) && strlen($password) < self::MINIMAL_PASSWORD_LENGTH) {
            $this->errors['password'] = self::SHORT_PASSWORD;
        }
    }

    /**
     * @param string $email
     */
    protected function validateEmail(string $email): void
    {
        if (!isset($this->errors['email']) && !preg_match('/^.+@.+\..+$/im', $email)) {
            $this->errors['email'] = self::INVALID_EMAIL;
        }
    }

    /**
     * @param $agreeTerms
     */
    protected function validateAgreeTerms($agreeTerms): void
    {
        if (!$agreeTerms) {
            $this->errors['agree_terms'] = self::AGREE_TERMS;
        }
    }

    /**
     * @param array $avatar
     */
    protected function validateAvatar(array $avatar): void
    {
        if (!in_array($avatar['type'], self::IMG_TYPES, true)) {
            $this->errors['avatar'] = self::INVALID_FILE_TYPE;
        } elseif ($avatar['size'] > self::MAX_FILE_SIZE_IN_BYTE) {
            $this->errors['avatar'] = self::TOO_BIG_FILE;
        }
    }

    /**
     * @param string $csrfToken
     */
    protected function validateCsrfToken(string $csrfToken): void
    {
        if ($_SESSION['csrf_token'] !== $csrfToken) {
            $this->errors['common'] = self::SIMPLE_MESSAGE_ERROR_FOR_USER;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function validateLoginForm(array $data): array
    {
        $this->validateCommonAuthField($data);
        return $this->errors;
    }

    /**
     * @param array $data
     * @return array
     */
    public function validateRegistrationForm(array $data): array
    {
        $this->validateCommonAuthField($data);

        if (isset($data['avatar'])) {
            $this->validateAvatar($data['avatar']);
        }

        $this->validateAgreeTerms($data['agree_terms']);
        return $this->errors;
    }

    /**
     * @param array $data
     */
    protected function validateCommonAuthField(array $data): void
    {
        $this->valuesIsEmpty($data);
        $this->validateEmail($data['email']);
        $this->validatePassword($data['password']);
        $this->validateCsrfToken($data['csrf_token']);
    }
}
