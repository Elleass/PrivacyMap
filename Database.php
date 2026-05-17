<?php

require_once "config.php";

class Database {
    private $username;
    private $password;
    private $host;
    private $database;
    private static $conn = null;

    public function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;
    }

    public function connect()
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        try {
            self::$conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

            return self::$conn;
        }
        catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function disconnect() {
        self::$conn = null;
    }
}
