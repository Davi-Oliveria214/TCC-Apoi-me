<?php
require(__DIR__ . '/../conexao.php');

$id = $_POST['resp'];

if (!empty($id)) {
    $res = request("contratados?id=eq.$id", "DELETE");
}