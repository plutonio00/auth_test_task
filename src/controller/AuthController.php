<?php

namespace app\controller;

use app\core\Application;
use app\core\exception\ApplicationException;
use app\form\AuthFormValidator;
use app\model\User;
use Exception;
use JsonException;

class AuthController extends AbstractController
{
    /**
     * @throws ApplicationException
     * @throws JsonException
     * @throws Exception
     */
    public function actionLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $this->cleanValue($_POST['email']),
                'password' => $this->cleanValue($_POST['password']),
                'csrf_token' => $this->cleanValue($_POST['csrf_token'])
            ];

            $validator = new AuthFormValidator();
            $errors = $validator->validateLoginForm($data);

            if ($errors) {
                echo json_encode(['status' => 'fail', 'errors' => $errors], JSON_THROW_ON_ERROR);
                return;
            }

            $data['remember_me'] = isset($_POST['remember_me']);

            $result = User::login($data);

            if (!($result instanceof User)) {
                echo json_encode(['status' => 'fail', 'errors' => $result], JSON_THROW_ON_ERROR);
                return;
            }

            echo json_encode(['status' => 'success'], JSON_THROW_ON_ERROR);
            return;
        }

        echo $this->renderPage('Login', 'auth/login', [
            'csrfToken' => $_SESSION['csrf_token'],
        ]);
    }

    /**
     * @throws ApplicationException
     * @throws JsonException
     */
    public function actionRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $this->cleanValue($_POST['email']),
                'password' => $this->cleanValue($_POST['password']),
                'first_name' => $this->cleanValue($_POST['first_name']),
                'last_name' => $this->cleanValue($_POST['last_name']),
                'agree_terms' => isset($_POST['agree_terms']),
                'csrf_token' => $this->cleanValue($_POST['csrf_token']),
            ];

            if ($_FILES['avatar']['name']) {
                $data['avatar'] = $_FILES['avatar'];
            }

            $validator = new AuthFormValidator();
            $errors = $validator->validateRegistrationForm($data);

            if ($errors) {
                echo json_encode(['status' => 'fail', 'errors' => $errors], JSON_THROW_ON_ERROR);
                return;
            }

            unset($data['csrf_token'], $data['agree_terms']);

            $result = User::registration($data);

            if (!($result instanceof User)) {
                echo json_encode(['status' => 'fail', 'errors' => $result], JSON_THROW_ON_ERROR);
                return;
            }

            echo json_encode(['status' => 'success'], JSON_THROW_ON_ERROR);
            return;

        }

        echo $this->renderPage('Registration', 'auth/registration', [
            'csrfToken' => $_SESSION['csrf_token'],
        ]);
    }

    public function actionLogout(): void
    {
        session_destroy();
        setcookie('auth_key', '', time() - 3600, '/');
        $router = Application::instance()->getRouter();
        $router->redirect('/auth/login');
    }
}
