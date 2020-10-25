<?php

namespace app\core;

use PDO;
use PDOException;
use app\core\exception\ApplicationException;
use PDOStatement;

class Database
{
    private PDO $pdo;
    private string $sql;

    /**
     * Database constructor.
     * @param string $connectionName
     * @throws ApplicationException
     */
    public function __construct($connectionName = 'db')
    {
        /** @var Application $app */
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

    /**
     * @param string $tableName
     * @param string $columns
     * @return $this
     */
    public function select(string $tableName, $columns = '*'): self
    {
        $this->sql = 'SELECT ';

        if (is_array($columns)) {
            //todo: add parameters from array to sql
        }
        else {
            $this->sql .= $columns;
        }

        $this->sql .= ' FROM ' . $tableName;

        return $this;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function where(string $param): self
    {
        $this->sql .= sprintf(' WHERE %s = ?', $param);
        return $this;
    }

    public function insert(string $tableName, array $params): Database
    {
        $this->sql = sprintf('INSERT INTO %s (', $tableName);
        $values = ' VALUES (';

        foreach ($params as $param) {
            $this->sql .= $param . ',';
            $values .= '?,';
        }

        $this->sql = substr($this->sql, 0, -1) . ')';
        $values = substr($values, 0, -1) . ')';

        $this->sql .= $values;

        return $this;
    }

    /**
     * @param array $params
     * @return bool|PDOStatement
     */
    public function exec(array $params = []) {
        $stmt = $this->pdo->prepare($this->sql);

        if ($params) {
            $stmt->execute($params);
        }
        else {
            $stmt->execute();
        }

        return $stmt;
    }
}
