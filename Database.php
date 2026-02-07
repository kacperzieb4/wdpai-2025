<?php

require_once "config.php";

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct() {}

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function connect(): PDO
    {
        if ($this->connection === null) {
            $this->connection = new PDO(
                "pgsql:host=" . HOST . ";port=5432;dbname=" . DATABASE,
                USERNAME,
                PASSWORD,
                ["sslmode" => "prefer"]
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }

        return $this->connection;
    }
}
