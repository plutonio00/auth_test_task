<?php

namespace app\controller;

class DefaultController extends AbstractController
{
    public function actionIndex()
    {
        $content = $this->renderView('index');
        echo $this->renderPage('Index', $content);
    }
}
