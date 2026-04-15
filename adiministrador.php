<?php
include('./includes/head.php');
include('./includes/topo.php');
?>


<div class="content">
  <div id="page-dashboard" class="page active">
    <div class="section-header">
      <div class="textos">
        <h1>Visão Geral</h1>
        <p>Resumo do condomínio em tempo real</p>
      </div>

      <button id="abrirModal" class="btn-abrir">🔑 Gerar Código</button>
    </div>
    
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




<div class="containerPerfil">

  <div class="header">
    <h1>Meu Perfil</h1>
    <p>Gerencie suas informações pessoais.</p>
  </div>

  <!-- Nome de Usuário -->
  <div class="cardPerfil">
    <div class="card-title">
      Informações Pessoais
    </div>

    <div class="field">
      <label>Nome de usuário</label>
      <div class="input-wrap">
        <span class="ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </span>
        <input type="text" id="username" placeholder="seu_usuario" value="joaosilva">
      </div>
      <p class="hint">Somente letras, números e underscores.</p>
    </div>

    <div class="field">
      <label>Nome completo</label>
      <div class="input-wrap">
        <span class="ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <path d="M16 2v4M8 2v4M3 10h18"/>
          </svg>
        </span>
        <input type="text" id="fullname" placeholder="Seu nome completo" value="João da Silva">
      </div>
    </div>

    <div class="divider"></div>
    <div class="actions">
      <button class="btn btn-ghost" onclick="resetField('username','joaosilva'); resetField('fullname','João da Silva')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveSection('nome')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Salvar
      </button>
    </div>
  </div>

  <!-- E-mail -->
  <div class="cardPerfil">
    <div class="card-title">
      Endereço de E-mail
    </div>

    <div class="field">
      <label>E-mail atual</label>
      <div class="input-wrap">
        <span class="ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="m22 7-10 7L2 7"/>
          </svg>
        </span>
        <input type="email" id="email" placeholder="seu@email.com" value="joao@exemplo.com">
      </div>
    </div>

    <div class="field">
      <label>Confirmar novo e-mail</label>
      <div class="input-wrap">
        <span class="ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="m22 7-10 7L2 7"/>
          </svg>
        </span>
        <input type="email" id="emailConfirm" placeholder="repita o e-mail">
      </div>
    </div>

    <div class="divider"></div>
    <div class="actions">
      <button class="btn btn-ghost" onclick="resetField('email','joao@exemplo.com'); resetField('emailConfirm','')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveSection('email')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Atualizar E-mail
      </button>
    </div>
  </div>

  <!-- Senha -->
  <div class="cardPerfil">
    <div class="card-title">
      Segurança — Senha
    </div>

    <div class="field">
      <label>Senha atual</label>
      <div class="input-wrap">
        <span class="ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </span>
        <input type="password" id="currentPw" placeholder="••••••••">
        <button class="toggle-pw" onclick="togglePw('currentPw',this)" tabindex="-1">
          <svg id="eye-currentPw" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
    </div>

    <div class="field">
      <label>Nova senha</label>
      <div class="input-wrap">
        <span class="ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </span>
        <input type="password" id="newPw" placeholder="Mínimo 8 caracteres" oninput="checkStrength(this.value)">
        <button class="toggle-pw" onclick="togglePw('newPw',this)" tabindex="-1">
          <svg id="eye-newPw" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
      <div class="pw-strength" id="pwStrength">
        <div class="strength-bars">
          <div class="strength-bar" id="sb1"></div>
          <div class="strength-bar" id="sb2"></div>
          <div class="strength-bar" id="sb3"></div>
          <div class="strength-bar" id="sb4"></div>
        </div>
        <span class="strength-label" id="strengthLabel"></span>
      </div>
    </div>

    <div class="field">
      <label>Confirmar nova senha</label>
      <div class="input-wrap">
        <span class="ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </span>
        <input type="password" id="confirmPw" placeholder="Repita a nova senha">
        <button class="toggle-pw" onclick="togglePw('confirmPw',this)" tabindex="-1">
          <svg id="eye-confirmPw" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
    </div>

    <div class="divider"></div>
    <div class="actions">
      <button class="btn btn-ghost" onclick="clearPasswords()">Cancelar</button>
      <button class="btn btn-primary" onclick="saveSection('senha')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Alterar Senha
      </button>
    </div>
  </div>

</div>

<!-- Toast -->
<div class="toast" id="toast">
  <div class="toast-dot" id="toastDot"></div>
  <span id="toastMsg">Salvo com sucesso!</span>
</div>





