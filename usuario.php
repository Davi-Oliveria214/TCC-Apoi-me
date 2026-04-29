<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');

$condominio = request("condominios?codigo=eq.{$_SESSION['codigo']}");
?>

<div class="contentUser">
  <div class="perfil-container">
    <header class="perfil-hero">
      <div class="perfil-center">
        <div class="avatar-wrapper">
          <img src="./icon/icone.png" alt="Avatar" class="avatar-img">
          <button class="btn-edit-avatar" title="Mudar foto">📷</button>
        </div>
      </div>
      <div class="hero-info">
        <h1><?php echo $nome ?></h1>

        <div class="badges-wrapper">
          <span class="badge-role">Tipo: <?php echo ucfirst($tipo_usuario) ?></span>
          <span class="badge-condo">Condominio: <?php echo $condominio[0]['nome'] ?? 'Não informado' ?></span>
        </div>

        <div class="detalhes-info">
          <p><strong>Endereço:</strong> <?php echo $condominio[0]['endereco'] ?? 'Não informado' ?></p>

          <p><strong>Conta criada:</strong> <?php echo date('d/m/Y', strtotime($user_date)); ?></p>
        </div>
      </div>
    </header>

    <div class="perfil-grid">
      <div class="perfil-card">
        <div class="card-content">
          <h3>Dados Pessoais</h3>
          <p><strong>Nome:</strong> <?php echo $nome ?></p>
        </div>
        <button type="button" class="btn-action-outline" onclick="editarPerfil('editar_nome')">Editar nome</button>
      </div>

      <div class="perfil-card">
        <div class="card-content">
          <h3>E-mail e Contato</h3>
          <p><strong>E-mail:</strong> <?php echo $email ?></p>
        </div>
        <button type="button" class="btn-action-outline" onclick="editarPerfil('editar_email')">Alterar email</button>
      </div>

      <div class="perfil-card">
        <div class="card-content">
          <h3>Segurança</h3>
          <p>Altere sua senha por aqui</p>
        </div>
        <button type="button" class="btn-action-outline">Trocar Senha</button>
      </div>

      <div class="perfil-card">
        <div class="card-content">
          <h3>Chave</h3>
          <p>Chave de acesso do condominio</p>
        </div>
        <button type="button" class="btn-action-outline" onclick="editarPerfil('editar_codigo')">Mudar chave</button>
      </div>
    </div>
  </div>

  <?php if ($tipo_usuario == 'sindico'): ?>
    <div id="page-dashboard" class="page active">
      <div class="section-headerADM">
        <div class="section-headerADM">
          <div class="textos">
            <h1>Visão Geral</h1>
            <p>Resumo do condomínio em tempo real</p>
          </div>

          <div class="acoes-adm">
            <button class="btn-criar-aviso" onclick="abrirModalAviso('<?php echo $_SESSION['id'] ?>', '<?php echo $_SESSION['codigo'] ?>')">
              + Criar Aviso
            </button>
          </div>
        </div>

        <div class="stats">
          <?php
          $resUsuarios = request("usuarios?codigo=eq.{$_SESSION['codigo']}&select=count");
          $totalUsuarios = $resUsuarios[0]['count'];

          $resServicos = request("servicos?codigo=eq.{$_SESSION['codigo']}&select=count");
          $totalServicos = $resServicos[0]['count'];

          $resAvisos = request("avisos?codigo=eq.{$_SESSION['codigo']}&select=count");
          $totalAvisos = $resAvisos[0]['count'];
          ?>

          <div class="stat-card">
            <div class="stat-label">Moradores cadastrados</div>
            <div class="stat-value" id="stat-moradores"><?php echo $totalUsuarios ?></div>
            <div class="stat-sub">via código de acesso</div>
          </div>

          <div class="stat-card">
            <div class="stat-label">Serviços cadastrados</div>
            <div class="stat-value" id="stat-moradores"><?php echo $totalServicos ?></div>
            <div class="stat-sub">via código de acesso</div>
          </div>

          <div class="stat-card">
            <div class="stat-label">Quantidade de avisos</div>
            <div class="stat-value" id="stat-moradores"><?php echo $totalAvisos ?></div>
            <div class="stat-sub">via código de acesso</div>
          </div>
        </div>
      </div>

      <div class="card-adm">
        <div class="card-titleadm">Quadro de avisos</div>
        <div id="dashboard-activity">
          <?php
          $avisos = request("avisos?codigo=eq.{$_SESSION['codigo']}");

          if (!empty($avisos) && !isset($avisos['error'])):
            foreach ($avisos as $aviso):
              $criado = date("d/m/Y H:i", strtotime($aviso['criado_em']));
              $data = date("d/m/Y", strtotime($aviso['data_evento']));
          ?>
              <div class="card-avisos">
                <div class="titulo-avisos">
                  <img src="./icon/icone.png" alt="">
                  <div>
                    <h2><?php echo $aviso['titulo'] ?></h2>
                    <span class="autor">Por: <?php echo $aviso['autor'] ?></span>
                  </div>
                </div>

                <div class="datas-aviso">
                  <span>Evento: <?php echo $data ?></span>
                  <span>Postado em: <?php echo $criado ?></span>
                </div>

                <p>
                  <?php echo $aviso['mensagem'] ?>
                </p>

                <div class="aviso-btn">
                  <button type="button" class="btn" onclick="opcoesAviso('editar_aviso','<?php echo $aviso['id'] ?>')">Editar</button>
                  <button type="button" class="btn" onclick="opcoesAviso('apagar_aviso','<?php echo $aviso['id'] ?>')">Apagar</button>
                </div>
              </div>
            <?php
            endforeach;
          else:
            ?>
            <div class="empty">
              <p>Nenhum aviso criado!</p>
            </div>
          <?php
          endif;
          ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include("./includes/rodape.php"); ?>