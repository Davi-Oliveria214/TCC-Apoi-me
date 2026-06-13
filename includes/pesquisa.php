<?php
require_once(__DIR__ . '/../conexao.php');
session_start();

$valor = $_GET['pesquisa'] ?? '';
$pagina = $_GET['pagina'] ?? 'publico';

if ($pagina === 'anunciar') {
    $id = $_SESSION['id'];

    $endpoint = "servicos?id_prestador=eq.$id";

    if (!empty($valor)) {
        $endpoint .= "&nome=ilike.*$valor*";
    }
} else {
    $endpoint = "servicos?status=eq.true&select=*,categorias(nome),usuarios(nome)";

    if (!empty($valor)) {
        $endpoint .= "&nome=ilike.*$valor*";
    }

    $endpoint .= "&order=criado.desc&limit=10";
}

$servicos = request($endpoint, "GET");

if ($pagina !== 'anunciar') :
    require_once(__DIR__ . '/card_servico.php');
    if (!empty($servicos) && !isset($servicos['error'])) :
        shuffle($servicos);
        foreach ($servicos as $servico) :
            renderCardServico($servico, 'publico', $_SESSION['id'] ?? null);
        endforeach;
    else:
        ?>
        <div class="sv-vazio-grid">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <h3>Nenhum serviço disponível</h3>
            <p>Seja o primeiro a anunciar um serviço no seu condomínio!</p>
            <a href="./anunciar.php" class="sv-btn-agendar">Anunciar serviço</a>
        </div>
        <?php endif;
else :
    require_once(__DIR__ . '/card_servico.php');
    if (!empty($servicos) && !isset($servicos['error'])):
        foreach ($servicos as $s):
            // Busca reservas específicas deste serviço
            $reservados = request("contratados?id_servico=eq.{$s['id']}&select=count");
            $s['num_reservas'] = $reservados[0]['count'] ?? 0;
            
            renderCardServico($s, 'anunciar', $_SESSION['id']);
        endforeach;
    else:
        ?>
        <div class="an-vazio">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14,2 14,8 20,8" />
                <line x1="12" y1="18" x2="12" y2="12" />
                <line x1="9" y1="15" x2="15" y2="15" />
            </svg>
            <h3>Nenhum serviço cadastrado</h3>
            <p>Crie seu primeiro serviço e comece a atender seu condomínio!</p>
        </div>
<?php endif;
endif;
?>