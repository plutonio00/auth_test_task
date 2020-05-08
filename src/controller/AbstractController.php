<?php

namespace app\controller;

use app\core\Application;

abstract class AbstractController
{
    protected function render(string $template, array $vars = []) {

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
}
