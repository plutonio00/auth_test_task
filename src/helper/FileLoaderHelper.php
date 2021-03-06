<?php

namespace app\helper;

use app\core\Application;
use app\core\exception\ApplicationException;

class FileLoaderHelper
{
    /**
     * @param array $file
     * @return string
     * @throws ApplicationException
     */
    public static function downloadFile(array $file): string
    {
        $uploadDir = Application::instance()->getConfig('upload_dir');
        $extension = pathinfo($file['name'])['extension'];
        $basename = uniqid('', true) . '.' . $extension;
        $uploadPath = $uploadDir . $basename;

        return move_uploaded_file($file['tmp_name'], $uploadPath) ? $basename : '';
    }
}
