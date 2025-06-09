<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '5432';
$dbname = $_ENV['DB_NAME'] ?? 'clientesdb';
$user = $_ENV['DB_USER'] ?? 'postgres';
$pass = $_ENV['DB_PASS'] ?? '';

$connStr = "host=$host port=$port dbname=$dbname user=$user password=$pass";

$conn = pg_connect($connStr);
if (!$conn) {
    die("Conexión fallida a PostgreSQL");
}

$batchSize = $_ENV['BATCH_SIZE'] ?? 500;
