<?php

class DB
{
    private $host = 'localhost';
    private $dbname = 'newsportal';
    private $username = 'root';
    private $password = '';

    private $connection;

    public function __construct()
    {
        try {

            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {

            die('Ошибка подключения к базе данных: ' . $e->getMessage());

        }
    }


    public function getAll($sql)
    {
        $result = $this->connection->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getOne($sql)
    {
        $result = $this->connection->query($sql);

        return $result->fetch(PDO::FETCH_ASSOC);
    }

 
    public function execute($sql)
    {
        return $this->connection->exec($sql);
    }

    public function executePrepared($sql, $params)
    {
        $statement = $this->connection->prepare($sql);
        return $statement->execute($params);
    }
}