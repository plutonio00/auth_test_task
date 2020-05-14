<?php

namespace app\controller;

use app\core\exception\ApplicationException;

class DefaultController extends AbstractController
{
    /**
     * @throws ApplicationException
     */
    public function actionIndex()
    {
        echo $this->renderPage('Index', 'index');
    }
}
