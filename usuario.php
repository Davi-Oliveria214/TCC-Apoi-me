<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<div class="contentAdm">
  <?php if ($tipo_usuario == 'sindico'): ?>
    <div id="page-dashboard" class="page active">
      <div class="section-headerADM">
        <div class="textos">
          <h1>Visão Geral</h1>
          <p>Resumo do condomínio em tempo real</p>
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
        <div class="card-titleadm">🕐 Atividade Recente</div>
        <div id="dashboard-activity">
          <div class="empty">
            <div class="empty-icon">📭</div>
            <p>Nenhuma atividade ainda. Gere o primeiro código!</p>
          </div>
        </div>
      </div>

      <div class="card-adm">
        <div class="card-titleadm">Criar Avisos</div>
        <div id="dashboard-activity"></div>
      </div>
    </div>
  <?php endif; ?>

  <div class="containerPerfil">
    <div class="headerPERFIL">
      <h1>Meu Perfil</h1>
      <p class="pPerfil">Gerencie suas informações pessoais.</p>
    </div>
    <div class="cardPerfil">
      <div class="card-titlePERFIL">
        Informações Pessoais
      </div>

      <div class="fieldPerfil">
        <label>Nome de usuário</label>
        <div class="input-wrap">
          <input type="text" id="username" placeholder="seu_usuario" value="<?php echo $nome ?>">
        </div>
      </div>

      <div class="fieldPerfil">
        <label>Nome completo</label>
        <div class="input-wrap">
          <input type="text" id="fullname" placeholder="Seu nome completo" value="<?php echo $nome ?>">
        </div>
      </div>

      <div class="divider"></div>
      <div class="actions">
        <button class="btnPerfil btn-ghostPerfil" onclick="resetField('username','<?php echo $nome ?>'); resetField('fullname','<?php echo $nome ?>')">Cancelar</button>
        <button class="btnPerfil btn-primaryPerfil " onclick="saveSection('nome')">
          Salvar
        </button>
      </div>
    </div>

    <div class="cardPerfil">
      <div class="card-titlePERFIL">
        Endereço de E-mail
      </div>

      <div class="fieldPerfil">
        <label>E-mail atual</label>
        <div class="input-wrap">
          <input type="email" id="email" placeholder="seu@email.com" value="<?php echo $email ?>">
        </div>
      </div>

      <div class="fieldPerfil">
      <label>Novo e-mail</label>
      <div class="input-wrap">
        <input type="email" id="emailConfirm" placeholder="Novo e-mail">
      </div>
    </div>

      <div class="fieldPerfil">
        <label>Confirmar novo e-mail</label>
        <div class="input-wrap">
          <input type="email" id="emailConfirm" placeholder="repita o e-mail">
        </div>
      </div>

      <div class="divider"></div>
      <div class="actions">
        <button class="btnPerfil btn-ghostPerfil" onclick="resetField('email','joao@exemplo.com'); resetField('emailConfirm','')">Cancelar</button>
        <button class="btnPerfil btn-primaryPerfil" onclick="saveSection('email')">
          Atualizar E-mail
        </button>
      </div>
    </div>

    <div class="cardPerfil">
      <div class="card-title">
        Segurança — Senha
      </div>

      <div class="fieldPerfil">
        <label>Senha atual</label>
        <div class="input-wrap">
          <input type="password" id="currentPw" placeholder="••••••••">
          <button class="toggle-pw" onclick="togglePw('currentPw',this)" tabindex="-1">
          </button>
        </div>
      </div>

      <div class="fieldPerfil">
        <label>Nova senha</label>
        <div class="input-wrap">
          <input type="password" id="newPw" placeholder="Mínimo 8 caracteres" oninput="checkStrength(this.value)">
          <button class="toggle-pw" onclick="togglePw('newPw',this)" tabindex="-1">
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

      <div class="fieldPerfil">
        <label>Confirmar nova senha</label>
        <div class="input-wrap">
          <input type="password" id="confirmPw" placeholder="Repita a nova senha">
          <button class="toggle-pw" onclick="togglePw('confirmPw',this)" tabindex="-1">
        </div>
      </div>


      <div class="actions">
        <button class="btnPerfil btn-ghostPerfil" onclick="clearPasswords()">Cancelar</button>
        <button class="btnPerfil btn-primaryPerfil" onclick="saveSection('senha')">
          Alterar Senha
        </button>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/rodape.php"); ?>