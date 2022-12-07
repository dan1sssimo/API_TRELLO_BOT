<?php

namespace db;


use PDO;

class DB
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=telegrambot;charset=UTF8", 'root', '');
    }

    public function insertDB($tableName, $tableFields): void
    {
        $insertDataFiltered = array();
        $filteredFields = implode(', ', array_keys($tableFields));
        foreach ($tableFields as $key => $value)
            $insertDataFiltered [] = "'" . $value . "'";
        $data = implode(', ', $insertDataFiltered);
        $query = "INSERT IGNORE INTO {$tableName} ({$filteredFields}) VALUES ({$data})";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }
}