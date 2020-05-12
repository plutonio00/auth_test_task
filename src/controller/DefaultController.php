<?php

namespace app\controller;

class DefaultController extends AbstractController
{
    /**
     * @throws \app\core\exception\ApplicationException
     */
    public function actionIndex()
    {
        $content = $this->renderView('index');
        echo $this->renderPage('Index', $content);
    }
}
