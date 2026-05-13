<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');

$condominio = request("condominios?codigo=eq.{$_SESSION['codigo']}");
?>

<main class="pag-usuario">
  <section class="us-hero">
    <div class="us-hero-bg"></div>
    <div class="us-hero-inner">

      <!-- Avatar -->
      <div class="us-avatar-wrap">
        <div class="us-avatar">
          <?php if (!empty($img)): ?>
            <img src="<?php echo htmlspecialchars($img) ?>" alt="<?php echo htmlspecialchars($nome) ?>">
          <?php else: ?>
            <span><?php echo strtoupper(substr($nome, 0, 2)) ?></span>
          <?php endif; ?>
        </div>
        <button class="us-avatar-btn" title="Mudar foto">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
            <circle cx="12" cy="13" r="4" />
          </svg>
        </button>
      </div>

      <!-- Infos -->
      <div class="us-hero-info">
        <h1><?php echo htmlspecialchars($nome) ?></h1>
        <div class="us-badges">
          <span class="us-badge us-badge-role"><?php echo ucfirst($tipo_usuario) ?></span>
          <span class="us-badge us-badge-condo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            </svg>
            <?php echo htmlspecialchars($condominio[0]['nome'] ?? 'Não informado') ?>
          </span>
        </div>
        <div class="us-detalhes">
          <span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
              <circle cx="12" cy="10" r="3" />
            </svg>
            <?php echo htmlspecialchars($condominio[0]['endereco'] ?? 'Endereço não informado') ?>
          </span>
          <span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" />
              <line x1="16" y1="2" x2="16" y2="6" />
              <line x1="8" y1="2" x2="8" y2="6" />
              <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Membro desde <?php echo date('d/m/Y', strtotime($user_date)) ?>
          </span>
        </div>
      </div>

      <!-- Links rápidos -->
      <div class="us-hero-acoes">
        <a href="./historico.php" class="us-btn-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <polyline points="12,8 12,12 14,14" />
            <path d="M3.05 11a9 9 0 1 0 .5-4" />
            <polyline points="3,3 3,7 7,7" />
          </svg>
          Histórico
        </a>
        <a href="./anunciar.php" class="us-btn-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
          Meus Serviços
        </a>
        <a href="./includes/logout.php" class="us-btn-link us-btn-logout">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16,17 21,12 16,7" />
            <line x1="21" y1="12" x2="9" y2="12" />
          </svg>
          Sair
        </a>
      </div>

    </div>
  </section>

  <div class="us-conteudo">

    <!-- ===== CARDS DE CONFIGURAÇÕES ===== -->
    <section class="us-secao">
      <h2 class="us-secao-titulo">Configurações da conta</h2>
      <div class="us-cards-grid">

        <div class="us-card">
          <div class="us-card-icone">
            <img src="./icon/user1.png" alt="">
          </div>
          <div class="us-card-txt">
            <h3>Dados Pessoais</h3>
            <p><?php echo htmlspecialchars($nome) ?></p>
          </div>
          <button class="us-btn-editar"
            onclick="abrirModal('editar_nome','<?php echo $_SESSION['id'] ?>')">Editar nome</button>
        </div>

        <div class="us-card">
          <div class="us-card-icone">
            <img src="./icon/email.png" alt="">
          </div>
          <div class="us-card-txt">
            <h3>E-mail e Contato</h3>
            <p><?php echo htmlspecialchars($email) ?></p>
          </div>
          <button class="us-btn-editar"
            onclick="abrirModal('editar_email','<?php echo $_SESSION['id'] ?>')">Alterar e-mail</button>
        </div>

        <div class="us-card">
          <div class="us-card-icone">
            <img src="./icon/lock.png" alt="">
          </div>
          <div class="us-card-txt">
            <h3>Segurança</h3>
            <p>Altere sua senha de acesso</p>
          </div>
          <button class="us-btn-editar"
            onclick="abrirModal('editar_senha','<?php echo $_SESSION['id'] ?>')">Trocar senha</button>
        </div>

        <div class="us-card">
          <div class="us-card-icone">
            <img src="./icon/chave.png" alt="">
          </div>
          <div class="us-card-txt">
            <h3>Chave de Acesso</h3>
            <p>Chave do condomínio</p>
          </div>
          <button class="us-btn-editar"
            onclick="abrirModal('editar_codigo','<?php echo $_SESSION['id'] ?>')">Mudar chave</button>
        </div>

      </div>
    </section>

    <!-- ===== PAINEL DO SÍNDICO ===== -->
    <?php if ($tipo_usuario == 'sindico'): ?>
      <section class="us-secao us-sindico">
        <div class="us-sindico-header">
          <div>
            <h2 class="us-secao-titulo">Painel do Síndico</h2>
            <p>Visão geral do condomínio em tempo real</p>
          </div>
          <button class="us-btn-aviso" onclick="abrirModal('aviso')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
              stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Criar Aviso
          </button>
        </div>

        <!-- Stats -->
        <?php
        $resUsuarios = request("usuarios?codigo=eq.{$_SESSION['codigo']}&select=count");
        $totalUsuarios = $resUsuarios[0]['count'] ?? 0;
        $resServicos = request("servicos?codigo=eq.{$_SESSION['codigo']}&select=count");
        $totalServicos = $resServicos[0]['count'] ?? 0;
        $resAvisos = request("avisos?codigo=eq.{$_SESSION['codigo']}&select=count");
        $totalAvisos = $resAvisos[0]['count'] ?? 0;
        ?>
        <div class="us-stats">
          <div class="us-stat">
            <div class="us-stat-icone">
              <img src="./icon/users.png" alt="">
            </div>
            <div>
              <span class="us-stat-num"><?php echo $totalUsuarios ?></span>
              <span class="us-stat-label">Moradores</span>
            </div>
          </div>
          <div class="us-stat">
            <div class="us-stat-icone">
              <img src="./icon/check.png" style="width: 75px;" alt="">
            </div>
            <div>
              <span class="us-stat-num"><?php echo $totalServicos ?></span>
              <span class="us-stat-label">Serviços</span>
            </div>
          </div>
          <div class="us-stat">
            <div class="us-stat-icone">
              <img src="./icon/sino.png" alt="">
            </div>
            <div>
              <span class="us-stat-num"><?php echo $totalAvisos ?></span>
              <span class="us-stat-label">Avisos</span>
            </div>
          </div>
        </div>

        <!-- Quadro de avisos do síndico -->
        <div class="us-adm-avisos">
          <h3>Quadro de avisos</h3>
          <div class="us-adm-lista" id="dashboard-activity">
            <?php
            $avisos = request("avisos?codigo=eq.{$_SESSION['codigo']}");
            if (!empty($avisos) && !isset($avisos['error'])):
              foreach ($avisos as $aviso):
                $criado = date("d/m/Y H:i", strtotime($aviso['criado_em']));
                $data   = date("d/m/Y", strtotime($aviso['data_evento']));
            ?>
                <div class="us-aviso-adm">
                  <div class="us-aviso-adm-header">
                    <div>
                      <strong><?php echo htmlspecialchars($aviso['titulo']) ?></strong>
                      <span>Por: <?php echo htmlspecialchars($aviso['autor']) ?></span>
                    </div>
                    <div class="us-aviso-adm-datas">
                      <span>Evento: <?php echo $data ?></span>
                      <span>Postado: <?php echo $criado ?></span>
                    </div>
                  </div>
                  <p><?php echo htmlspecialchars($aviso['mensagem']) ?></p>
                  <div class="us-aviso-adm-acoes">
                    <button class="us-btn-adm us-btn-adm-editar"
                      onclick="abrirModal('editar_aviso','<?php echo $aviso['id'] ?>')">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                      </svg>
                      Editar
                    </button>
                    <button class="us-btn-adm us-btn-adm-apagar"
                      onclick="abrirModal('apagar_aviso','<?php echo $aviso['id'] ?>')">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3,6 5,6 21,6" />
                        <path d="M19 6l-1 14H6L5 6" />
                        <path d="M10 11v6M14 11v6" />
                        <path d="M9 6V4h6v2" />
                      </svg>
                      Apagar
                    </button>
                  </div>
                </div>
              <?php
              endforeach;
            else:
              ?>
              <div class="us-adm-vazio">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                  <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                <p>Nenhum aviso criado ainda.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
  </div>
</main>

<?php include("./includes/rodape.php"); ?>