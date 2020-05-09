<?php

namespace app\controller;

session_start();

class DefaultController extends AbstractController
{
    public function actionIndex()
    {
        if (isset($_GET['logout']) && $_GET['logout']) {
            unset($_SESSION['email']);
            unset($_SESSION['pass']);
            unset($_SESSION['userId']);
            session_destroy();
        }

        $content = $this->renderView('index', [
            'greet' => 'hello',
        ]);

        echo $this->renderPage('Index', $content);
    }
}
