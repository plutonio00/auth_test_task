<?php

namespace app\core;

class Database
{
    private $pdo;

    public function __construct()
    {
        try {
            //todo: add connection
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            echo "Can't connect to database";
        }
    }
}
