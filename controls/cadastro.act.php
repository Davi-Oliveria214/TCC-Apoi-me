<?php
require('../includes/conexao.php');
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$chave = $_POST['chave'];

$sql = mysqli_query($con, "SELECT * FROM usuario WHERE email;");

$sql_chave = mysqli_query($con, "SELECT * FROM condominio;");

$id_chave = mysqli_fetch_array($sql_chave);

if ($sql !== null || empty($sql)) {
    mysqli_query($con, "INSERT INTO usuario(nome, email, senha, foto, id_condominio) 
    VALUES ('$nome', '$email', '$senha', '23dsx', $id_chave[id])");
}

header("Location: ../cadastro.php");