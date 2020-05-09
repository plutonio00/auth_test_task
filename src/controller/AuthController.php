<?php

namespace app\controller;

use app\core\Application;
use app\form\AuthFormValidator;
use app\model\User;

session_start();

class AuthController extends AbstractController
{
    public function actionLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => strip_tags($_POST['email']),
                'password' => strip_tags($_POST['password']),
            ];

            $validator = new AuthFormValidator();
            $errors = $validator->validateLoginForm($data);

            if ($errors) {
                echo json_encode(['status' => 'fail', 'errors' => $errors]);
                return;
            }

            $data['remember_me'] = $_POST['remember_me'];

            $result = User::login($data);

            if ($result instanceof User) {
                echo json_encode(['status' => 'success']);
                return;
            } else {
                echo json_encode(['status' => 'fail', 'errors' => $result]);
                return;
            }
        }

        $content = $this->renderView('auth/login');
        echo $this->renderPage('Login', $content);
    }

    public function actionRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => strip_tags($_POST['email']),
                'password' => strip_tags($_POST['password']),
                'first_name' => strip_tags($_POST['first_name']),
                'last_name' => strip_tags($_POST['last_name']),
            ];
        }

        $content = $this->renderView('auth/registration');
        echo $this->renderPage('Registration', $content);
    }

    public function actionLogout()
    {
        session_destroy();
        setcookie('email', '', time() - 3600, '/');
        setcookie('password', '', time() - 3600, '/');
        $router = Application::instance()->getRouter();
        $router->redirect('/auth/login');
    }
}
