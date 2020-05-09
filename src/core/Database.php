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
        if ($actionType === 'insert') {
            $this->pdo->query($sql);
            return $this->pdo->lastInsertId('id');
        }

        if ($params) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
