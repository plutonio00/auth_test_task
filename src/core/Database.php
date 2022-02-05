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
        $app = Application::instance();

        try {
            $dbConfig = $app->getConfig($connectionName);

            $pass = $dbConfig['password'];
            $user = $dbConfig['user'];
            $dbName = $dbConfig['db_name'];
            $host = $dbConfig['host'];
            $dsn = sprintf('mysql:dbname=%s;host=%s', $dbName, $host);
            $this->pdo = new PDO($dsn, $user, $pass);
			
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo 'Can\'t connect to database';
        }
		
		var_dump($this);die;
    }

    /**
     * @param string $tableName
     * @param string|array $columns
     * @return $this
     */
    public function select(string $tableName, $columns): self
    {
        $this->sql = 'SELECT ';
        $select = is_array($columns) ? implode(',', $columns) : $columns;
        $this->sql .= sprintf('%s FROM %s', $select, $tableName);
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

    public function insert(string $tableName, array $columns): Database
    {
        $this->sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $tableName,
            implode(',', $columns),
            implode(',', array_fill(0, count($columns), '?')),
        );
        return $this;
    }

    public function update(string $tableName, array $columns): Database
    {
        $this->sql = sprintf('UPDATE %s SET ', $tableName);

        $preparedParams = [];

        foreach ($columns as $column) {
            $preparedParams[] = sprintf('%s = %s', $column, '?');
            $this->sql .= implode(',', $preparedParams);
        }
        return $this;
    }

    /**
     * @param array $params
     * @return bool|PDOStatement
     */
    public function exec(array $params = [])
    {
        $stmt = $this->pdo->prepare($this->sql);
        $params ? $stmt->execute($params) : $stmt->execute();
        return $stmt;
    }
}
