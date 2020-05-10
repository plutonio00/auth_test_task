<?php

namespace app\controller;

class DefaultController extends AbstractController
{
    public function actionIndex()
    {
        $content = $this->renderView('index', [
            'greet' => 'hello',
        ]);

        echo $this->renderPage('Index', $content);
    }
}
