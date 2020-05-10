<?php

namespace app\form;

class AuthFormValidator
{
    const EMPTY_VALUE = 'This field needs to be filled';
    const MINIMAL_PASSWORD_LENGTH = 6;
    const SHORT_PASSWORD = 'Password is too short. Type at least ' . self::MINIMAL_PASSWORD_LENGTH . ' symbols';
    const AGREE_TERMS = 'You should agree with these terms';
    const INVALID_EMAIL = 'This email is incorrect';
    const INVALID_CSRF_TOKEN = 'Invalid csrf token';
    const SIMPLE_MESSAGE_ERROR_FOR_USER = 'Error. Please, reload the page';
    const IMG_TYPES = [
        'jpg', 'jpeg', 'png', 'bpm',
    ];
    private $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    protected function valuesIsEmpty(array $values)
    {
        foreach ($values as $key => $value) {
            if (empty($value)) {
                $this->errors[$key] = self::EMPTY_VALUE;
            }
        }
    }

    protected function validatePassword(string $password)
    {
        if (!isset($this->errors['password']) && strlen($password) < self::MINIMAL_PASSWORD_LENGTH) {
            $this->errors['password'] = self::SHORT_PASSWORD;
        }
    }

    protected function validateEmail(string $email)
    {
        if (!isset($this->errors['email']) && !preg_match('/^.+@.+\..+$/im', $email)) {
            $this->errors['email'] = self::INVALID_EMAIL;
        }
    }

    protected function validateAgreeTerms($agreeTerms) {
        if (!$agreeTerms) {
            $this->errors['agree_terms'] = self::AGREE_TERMS;
        }
    }

    protected function validateAvatar(array $avatar) {

    }

    protected function validateCsrfToken(string $csrfToken) {
        if ($_SESSION['csrf_token'] !== $csrfToken) {
            $this->errors['common'] = self::SIMPLE_MESSAGE_ERROR_FOR_USER;
        }
    }

    public function validateLoginForm(array $data)
    {
        $this->validateCommonAuthField($data);
        return $this->errors;
    }

    public function validateRegistrationForm(array $data)
    {
        $this->validateCommonAuthField($data);
       // $this->validateAvatar($data['avatar']);
        $this->validateAgreeTerms($data['agree_terms']);
        return $this->errors;
    }

    protected function validateCommonAuthField(array $data) {
        $this->valuesIsEmpty($data);
        $this->validateEmail($data['email']);
        $this->validatePassword($data['password']);
        $this->validateCsrfToken($data['csrf_token']);
    }
}
