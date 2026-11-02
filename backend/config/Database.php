
<?php

class Database
{
    public static function connect()
    {
        $config = require __DIR__ . "/config.php";

        $host = $config["host"];
        $dbname = $config["dbname"];
        $username = $config["username"];
        $password = $config["password"];

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $pdo->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );

            return $pdo;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new RuntimeException("Database connection failed.");
        }
    }
}
