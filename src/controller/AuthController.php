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
                echo json_encode($errors);
                return;
            }

            $result = User::login($data);

            if ($result instanceof User) {
                $router = Application::instance()->getRouter();
                $router->redirect('/');
            }
            else {
                echo json_encode($result);
                return;
            }
        }

        $content = $this->renderView('auth/login');
        echo $this->renderPage('Login', $content);
    }

    public function actionRegister()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        $data = [
            'email' => strip_tags($_POST['email']),
            'pass' => strip_tags($_POST['hashPass']),
        ];
    }
}
