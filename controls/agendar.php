<?php
@session_start();

if (empty($_SESSION['login']) || empty($_SESSION['id'])) {
    $_SESSION['mensagem'] = 'Faça login para poder agendar o serviço!!!';
    header('Location: ../login.php');
    exit();
}

$_SESSION['mensagem'] = 'Página de agendamento em desenvolvimento!!!';
header('Location: ../index.php');
exit();