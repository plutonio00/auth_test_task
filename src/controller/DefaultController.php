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
        $content = $this->renderView('index');
        echo $this->renderPage('Index', $content);
    }
}
