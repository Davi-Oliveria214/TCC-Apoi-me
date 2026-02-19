<?php
$senha = file_get_contents('.env');

$con = mysqli_connect('127.0.0.1:3307', 'root', $senha, 'bd_apoi_me');
if (!$con) {
    die("Erro: " . mysqli_connect_error());
}

mysqli_query($con, "SET NAMES utf8");
?>