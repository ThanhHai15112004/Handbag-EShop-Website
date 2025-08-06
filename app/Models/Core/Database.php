<?php

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    
    private function __construct()
    {
        $this->initializeConnection();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function initializeConnection(): void
    {
        $config = $this->loadEnvironmentVariables();

        $host = $config['DB_HOST'];
        $databaseName = $config['DB_NAME'];
        $username = $config['DB_USER'];
        $password = $config['DB_PASS'];

        $dsn = "mysql:host=$host;dbname=$databaseName;charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Không thể kết nối đến cơ sở dữ liệu: " . $exception->getMessage());
        }
    }

    private function loadEnvironmentVariables(): array
    {
        $envPath = __DIR__ . '/../../configs/.env';

        if (!file_exists($envPath)) {
            die("Không tìm thấy file cấu hình .env tại: $envPath");
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = [];

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $config[trim($key)] = trim($value);
        }

        return $config;
    }
}
