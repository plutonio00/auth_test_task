<?php

namespace app\core;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct($connectionName = 'db')
    {
        $app = Application::instance();

        try {
            $pass = $app->getConfig($connectionName)['pass'];
            $user = $app->getConfig($connectionName)['user'];
            $name = $app->getConfig($connectionName)['name'];
            $host = $app->getConfig($connectionName)['host'];
            $dsn = sprintf('mysql:dbname=%s;host=%s', $name, $host);

            $this->pdo = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo 'Can\'t connect to database';
        }
    }

    public function customQuery(string $sql, string $actionType, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if ($actionType === 'insert') {
            return $this->pdo->lastInsertId('id');
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
}
