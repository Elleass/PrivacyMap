<?php

require_once __DIR__."/../../Database.php";

class Repository {
    protected $database;
    protected $connection;

    public function __construct() {
        $this->database = new Database();
        $this->connection = $this->database->connect();
    }
}
