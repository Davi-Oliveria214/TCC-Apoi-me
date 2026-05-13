<?php
session_start();

$mensagemDesejada = isset($_GET['msg']) ? $_GET['msg'] : "Você saiu com segurança.";

session_unset();

session_destroy();

session_start();
$_SESSION['mensagem'] = $mensagemDesejada;

?>
<script>
    usuarioLogado = false;
</script>
<?php

header("Location: ../login.php");
exit();