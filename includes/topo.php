<?php
require_once(__DIR__ . '/../conexao.php');
@session_start();

$pag = basename($_SERVER['PHP_SELF']);
$local = ($pag != 'anunciar.php') ? 'publico' : 'anunciar';

if ($pag == 'cadastro.php') {
    $class = 'page-cadastro';
} elseif ($pag == 'login.php') {
    $class = 'page-login';
} elseif ($pag == 'contato.php') {
    $class = 'page-contato';
}

function navAtivo($paginas)
{
    global $pag;
    return in_array($pag, (array) $paginas) ? ' class="ativo"' : '';
}

include_once './util/avisos.php';
?>

<header>
    <?php
    if (!empty($_SESSION["id"])):
        $id = $_SESSION["id"];
        $res = request("usuarios?id=eq.$id&select=nome,email,imagem,codigo,tipo_usuario,user_date", "GET");
        if (!empty($res) && !isset($res['error'])) {
            $usuario = $res[0];
            $nome = $usuario['nome'];
            $email = $usuario['email'];
            $img = $usuario['imagem'];
            $tipo_usuario = $usuario['tipo_usuario'];
            $user_date = $usuario['user_date'];
            $_SESSION['codigo'] = $usuario['codigo'];
        }
    endif;
    ?>

    <a class="logo" href="../index.php">
        <div class="logo-marca">A</div>
        <span class="logo-texto">Apoie-me</span>
    </a>

    <div class="header-busca">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.35-4.35" />
        </svg>
        <input type="text" name="pesquisa" id="pesquisa"
            placeholder="Buscar serviços…"
            oninput="pesquisa('<?php echo $local ?>',this.value)">
    </div>

    <div class="header-sep"></div>

    <div class="header-nav-desktop">
        <ul class="nav-links">
            <li><a href="../index.php" <?php echo navAtivo('index.php') ?>>Início</a></li>
            <?php if (!empty($id)) : ?>
                <li><a href="../servicos.php" <?php echo navAtivo('servicos.php') ?>>Serviços</a></li>
                <li><a href="../usuario.php" <?php echo navAtivo('usuario.php') ?>>Perfil</a></li>
                <li><a href="../historico.php" <?php echo navAtivo('historico.php') ?>>Histórico</a></li>
                <li><a href="../mensagens.php" <?php echo navAtivo('mensagens.php') ?>>Mensagens</a></li>
            <?php endif; ?>
            <li><a href="../sobre.php" <?php echo navAtivo('sobre.php') ?>>Sobre</a></li>
            <li><a href="../contato.php" <?php echo navAtivo('contato.php') ?>>Contato</a></li>
            <?php if (!empty($id)) : ?>
                <li><a href="../includes/logout.php" class="link-sair">Sair</a></li>
            <?php endif; ?>
        </ul>

        <?php if (empty($id)) : ?>
            <a href="../login.php" class="btn-entrar">
                Entrar
            </a>
        <?php endif; ?>
    </div>

    <button id="burguer" aria-label="Abrir menu" aria-expanded="false">
        <div></div>
        <div></div>
        <div></div>
    </button>

    <script>
        var usuarioLogado = <?php echo !empty($id) ? 'true' : 'false' ?>;
    </script>
</header>

<nav class="fundo-topo" aria-label="Menu mobile">
    <ul class="nav-links">
        <li><a href="../index.php" <?php echo navAtivo('index.php') ?>>Início</a></li>
        <?php if (!empty($id)) : ?>
            <li><a href="../servicos.php" <?php echo navAtivo('servicos.php') ?>>Serviços</a></li>
            <li><a href="../usuario.php" <?php echo navAtivo('usuario.php') ?>>Perfil</a></li>
            <li><a href="../historico.php" <?php echo navAtivo('historico.php') ?>>Histórico</a></li>
                <li><a href="../mensagens.php" <?php echo navAtivo('mensagens.php') ?>>Mensagens</a></li>
        <?php endif; ?>
        <li><a href="../sobre.php" <?php echo navAtivo('sobre.php') ?>>Sobre</a></li>
        <li><a href="../contato.php" <?php echo navAtivo('contato.php') ?>>Contato</a></li>
        <?php if (!empty($id)) : ?>
            <li><a href="../includes/logout.php" class="link-sair">Sair</a></li>
        <?php endif; ?>
    </ul>

    <?php if (empty($id)) : ?>
        <a href="../login.php" class="btn-entrar">
            Entrar
        </a>
    <?php endif; ?>
</nav>

<div id="loadModal"></div>

<?php if (!empty($class)) : ?>
    <div class="<?php echo $class ?>">
    <?php endif; ?>