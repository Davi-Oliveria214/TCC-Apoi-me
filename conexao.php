<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$name = $_ENV['DB_NAME'];

// Exemplo de uso na conexão:
$con = mysqli_connect($host, $user, $pass, $name);

if (!$con) {
    die("Falha na conexão: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");