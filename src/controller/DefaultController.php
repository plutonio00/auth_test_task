<?php

namespace app\controller;

use app\core\Application;
use app\core\exception\ApplicationException;

class DefaultController extends AbstractController
{
    /**
     * @throws ApplicationException
     */
    public function actionIndex()
    {
        $app = Application::instance();
        $router = $app->getRouter();

        if (isset($_SESSION['user'])) {
            $router->redirect('/user/profile');
        }

        $router->redirect('/auth/login');
    }
}
