<?php

namespace app\form;

class AuthFormValidator
{
    const EMPTY_VALUE = 'This field needs to be filled';
    const MINIMAL_PASSWORD_LENGTH = 6;
    const SHORT_PASSWORD = 'Password is too short. Type at least ' . self::MINIMAL_PASSWORD_LENGTH . ' symbols';
    const AGREE_TERMS = 'You should agree with these terms';
    const INCORRECT_EMAIL = 'This email is incorrect';
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
            $this->errors['email'] = self::INCORRECT_EMAIL;
        }
    }

    protected function validateAgreeTerms(bool $agreeTerms) {
        if (!$agreeTerms) {
            $this->errors['agree_terms'] = self::AGREE_TERMS;
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
        $this->validateAgreeTerms($data['agree_terms']);
        return $this->errors;
    }

    protected function validateCommonAuthField(array $data) {
        $this->valuesIsEmpty($data);
        $this->validateEmail($data['email']);
        $this->validatePassword($data['password']);
    }
}
