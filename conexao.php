<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$banco = $_ENV['DB_BANCO'];
$port = $_ENV['DB_PORT'];

try {
    $dns  = "pgsql:host=$host;port=$port;dbname=$banco";

    $con = new PDO($dns, $user, $pass);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Falha na conexão" . $e->getMessage());
}