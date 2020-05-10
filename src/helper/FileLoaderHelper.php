<?php

namespace app\helper;

use app\core\Application;

class FileLoaderHelper
{
    public static function downloadFile(array $file) {
        $uploadDir = Application::instance()->getConfig('upload_dir');
        $extension = pathinfo($file['name'])['extension'];
        $basename = uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $basename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $basename;
        }
    }
}
