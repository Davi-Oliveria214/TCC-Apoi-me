<?php
session_start();

$email_url = $_GET['email'] ?? '';
$codigo_url = $_GET['codigo'] ?? '';
$tipo_codigo = $_GET['tipo_codigo'] ?? '';

if (empty($email_url)) {
    $_SESSION["mensagem"] = "Acesso inválido.";
    header("Location: ./login.php");
    exit;
}

if (isset($_SESSION['id_usuario']) && $_SESSION['email_usuario'] !== $email_url) {
    session_unset();
    session_destroy();

    session_start();
    $_SESSION['mensagem'] = "Sessão anterior encerrada para prosseguir com a recuperação.";
}

$_SESSION['email_verificar'] = $email_url;
$_SESSION['tipo_codigo'] = $tipo_codigo;

include("./includes/head.php");
include("./includes/topo.php");
?>

<main class="autenticar">
    <div class="div-form">
        <form action="./controls/verificar.act.php" method="post" class="form ativar-load">
            <h1>Confirmar Código</h1>
            <p>Digite o código enviado para seu e-mail.</p>

            <input type="hidden" name="tipo_codigo" value="<?php echo $tipo_codigo; ?>">

            <input type="hidden" name="email_recuperar" value="<?php echo $email_url; ?>">

            <div class="box-auth">
                <label>Código de 6 dígitos</label>
                <input type="text" name="codigo" value="<?php echo $codigo_url; ?>" maxlength="6" required>
            </div>

            <div class="box-btn">
                <button type="submit" class="btn btn-auth">Verificar</button>
            </div>
        </form>
    </div>
</main>

<?php include("./includes/rodape.php"); ?>