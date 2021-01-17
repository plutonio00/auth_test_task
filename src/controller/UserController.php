<?php

namespace app\controller;

use app\core\exception\ApplicationException;
use app\model\User;

class UserController extends AbstractController
{
    /**
     * @return string
     * @throws ApplicationException
     */
    public function actionProfile(): void {
        $idUser = $_SESSION['user']['id'];

        $userRaw = User::findByField('id', $idUser);
        $user = new User($userRaw);

        echo $this->renderPage('Profile', 'user/profile', [
            'user' => $user,
        ]);
    }
}