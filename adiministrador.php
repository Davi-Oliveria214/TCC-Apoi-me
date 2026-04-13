<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<!-- Pages -->
<div class="content">
  <div id="page-dashboard" class="page active">
    <div class="section-header">
      <div class="textos">
        <h1>Visão Geral</h1>
        <p>Resumo do condomínio em tempo real</p>
      </div>

      <button id="abrirModal" class="btn-abrir">🔑 Gerar Código</button>
    </div>
    <!-- MODAL FORM -->
    <div id="modal-form" class="modal">
      <div class="modal-content">
        <h2>Gerar Código</h2>

        <input
          type="text"
          id="nome-condominio"
          placeholder="Nome do Condomínio" />

        <input type="text" id="endereco" placeholder="Endereço" />

        <button id="gerarCodigo" class="btn-gerar">🔑 Gerar Código</button>
        <button id="fecharModal" class="btn-fechar">Cancelar</button>

        <div id="codigoGerado" style="display: none"></div>
      </div>
    </div>

    <div class="stats">
      <div class="stat-card">
        <div class="stat-label">Moradores Cadastrados</div>
        <div class="stat-value" id="stat-moradores">0</div>
        <div class="stat-sub">via código de acesso</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Códigos Ativos</div>
        <div class="stat-value" id="stat-ativos">0</div>
        <div class="stat-sub">aguardando uso</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Códigos Utilizados</div>
        <div class="stat-value" id="stat-usados">0</div>
        <div class="stat-sub">desde o início</div>
      </div>
    </div>
  </div>

  <div class="card-adm">
    <div class="card-title">🕐 Atividade Recente</div>
    <div id="dashboard-activity">
      <div class="empty">
        <div class="empty-icon">📭</div>
        <p>Nenhuma atividade ainda. Gere o primeiro código!</p>
      </div>
    </div>
  </div>

  <div class="card-adm">
    <div class="card-title">Criar Avisos</div>
    <div id="dashboard-activity"></div>
  </div>
</div>