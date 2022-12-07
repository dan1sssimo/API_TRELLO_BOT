<?php

namespace telegramBot;


use db\DB;

class TelegramBot
{
    public static $dbConnection;

    public function __construct()
    {
        self::$dbConnection = new DB();
    }

    public function insertUser($tableName, $userID, $userName): void
    {
        self::$dbConnection->insertDB($tableName, ["user_id" => $userID, "user_name" => $userName]);
    }

}
