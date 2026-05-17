<?php
require_once './includes/funcoes.php';
loginFeito();
?>
<?php include_once './includes/head.php'; ?>
<?php include_once './includes/topo.php' ?>

<div class="painel-visual">
    <div class="painel-visual-bg"></div>

    <div class="painel-topo"></div>

    <div class="painel-rodape">
        <h2>Tudo que seu condomínio precisa em um só lugar</h2>
        <div class="beneficios">
            <div class="beneficio">
                <div class="beneficio-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
                <div class="beneficio-txt">
                    <strong>Profissionais verificados</strong>
                    <span>Todos os prestadores são moradores do seu condomínio</span>
                </div>
            </div>
            <div class="beneficio">
                <div class="beneficio-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <div class="beneficio-txt">
                    <strong>Contratação segura</strong>
                    <span>Histórico, avaliações e agendamento transparentes</span>
                </div>
            </div>
            <div class="beneficio">
                <div class="beneficio-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20,6 9,17 4,12" />
                    </svg>
                </div>
                <div class="beneficio-txt">
                    <strong>Simples e rápido</strong>
                    <span>Cadastro gratuito, sem complicação</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="painel-form">
    <h1>Bem-vindo ao Apoie-me</h1>

    <div class="form-wrapper">
        <div class="form-header">
            <span class="subtag">Crie sua conta</span>
            <p>Preencha os dados abaixo para começar.</p>
        </div>

        <div class="tipo-selector">
            <label class="tipo-opcao">
                <input type="radio" name="tipo_visual" value="morador" checked onchange="tipoChange(this)">
                <div class="tipo-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9,22 9,12 15,12 15,22" />
                    </svg>
                    <strong>Morador</strong>
                    <small>Quero contratar serviços</small>
                </div>
            </label>
            <label class="tipo-opcao">
                <input type="radio" name="tipo_visual" value="sindico" onchange="tipoChange(this)">
                <div class="tipo-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                    </svg>
                    <strong>Síndico</strong>
                    <small>Gerencio o condomínio</small>
                </div>
            </label>
        </div>

        <form action="./controls/auth.act.php" method="post">

            <input type="hidden" name="tipo_usuario" id="tipo_usuario_hidden" value="morador">
            <input type="hidden" name="acao" value="cadastro">

            <div class="campos-grid">

                <div class="campo campo-full">
                    <label for="idNome">Nome completo</label>
                    <input type="text" name="nome" id="idNome" placeholder="Seu nome" required autocomplete="name">
                </div>

                <div class="campo campo-full">
                    <label for="idEmail">E-mail</label>
                    <input type="email" name="email" id="idEmail" placeholder="seu@email.com"
                        onkeydown="if(event.key == ' ') event.preventDefault()" required autocomplete="email">
                </div>

                <div class="campo">
                    <label for="idSenha">Senha</label>
                    <div class="campo-input-wrap">
                        <input type="password" name="senha" id="idSenha" minlength="8"
                            onkeydown="if(event.key === ' ') event.preventDefault()"
                            oninput="verificarSenha(); checarForca(this.value)"
                            placeholder="Mín. 8 caracteres" required autocomplete="new-password">
                        <button type="button" class="olho-btn" onclick="toggleSenha('idSenha', this)" aria-label="Mostrar senha">
                            <img id="olho-idSenha" src="./icon/visibility.png" alt="Mostrar">
                        </button>
                    </div>
                    <div class="forca-barra">
                        <div class="forca-seg" id="f1"></div>
                        <div class="forca-seg" id="f2"></div>
                        <div class="forca-seg" id="f3"></div>
                        <div class="forca-seg" id="f4"></div>
                    </div>
                    <span class="forca-texto" id="forca-txt"></span>
                </div>

                <div class="campo">
                    <label for="idRptSenha">Confirmar senha</label>
                    <div class="campo-input-wrap">
                        <input type="password" name="rptSenha" id="idRptSenha" minlength="8"
                            onkeydown="if(event.key === ' ') event.preventDefault()"
                            oninput="verificarSenha()"
                            placeholder="Repita a senha" required autocomplete="new-password">
                        <button type="button" class="olho-btn" onclick="toggleSenha('idRptSenha', this)" aria-label="Mostrar senha">
                            <img id="olho-idRptSenha" src="./icon/visibility.png" alt="Mostrar">
                        </button>
                    </div>
                    <span id="senha-match" style="font-size:12px; min-height:16px; display:block;"></span>
                </div>

                <div class="campo campo-cnpj campo-full" id="campoCnpj">
                    <label for="cnpjId">CNPJ do condomínio</label>
                    <input type="text" name="cnpj_condominio" id="cnpjId" placeholder="XX.XXX.XXX/XXXX-XX" maxlength="18">
                </div>

            </div>

            <button type="submit" id="btnEnviar" class="btn-submit">
                Criar minha conta gratuita
            </button>
        </form>

        <p class="form-footer-txt">Já tem uma conta? <a href="./login.php">Fazer login →</a></p>
    </div>
</div>

</div>

<?php include_once './includes/rodape.php'; ?>