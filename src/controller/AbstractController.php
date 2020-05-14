<?php

namespace app\controller;

use app\core\Application;
use app\core\exception\ApplicationException;

abstract class AbstractController
{
    /**
     * @param string $template
     * @param array $vars
     * @return false|string
     * @throws ApplicationException
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
     * @param string $pageName
     * @param string $template
     * @param array $params
     * @return false|string
     * @throws ApplicationException
     */
    protected function renderPage(string $pageName, string $template, array $params = [])
    {
        $js = Application::instance()->getConfig('jsFiles')[$pageName];
        $content = $this->renderView($template, $params);

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
