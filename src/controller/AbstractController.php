<?php

namespace app\controller;

use app\core\Application;

abstract class AbstractController
{
    /**
     * @param string $template
     * @param array $vars
     * @return false|string
     * @throws \app\core\exception\ApplicationException
     */
    protected function renderView(string $template, array $vars = []) {

        if ($vars) {
            extract($vars);
        }

        ob_start();

        if (!empty($template)) {
            $path = Application::instance()->getConfig('tmpl_dir') . $template . '.php';
            include($path);
        }

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @param $pageName
     * @param $content
     * @return false|string
     * @throws \app\core\exception\ApplicationException
     */
    protected function renderPage($pageName, $content)
    {
        $js = Application::instance()->getConfig('jsFiles')[$pageName];

        return $this->renderView('layout/layout', [
            'pageName' => $pageName,
            'content' => $content,
            'js' => $js
        ]);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function cleanValue(string $value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value);

        return $value;
    }
}
