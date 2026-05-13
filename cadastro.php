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
                    <img src="./icon/check-circle.png" alt="Verificados" width="18" height="18">
                </div>
                <div class="beneficio-txt">
                    <strong>Profissionais verificados</strong>
                    <span>Todos os prestadores são moradores do seu condomínio</span>
                </div>
            </div>
            <div class="beneficio">
                <div class="beneficio-icone">
                    <img src="./icon/shield.png" alt="Segurança" width="18" height="18">
                </div>
                <div class="beneficio-txt">
                    <strong>Contratação segura</strong>
                    <span>Histórico, avaliações e agendamento transparentes</span>
                </div>
            </div>
            <div class="beneficio">
                <div class="beneficio-icone">
                    <img src="./icon/zap.png" alt="Rápido" width="18" height="18">
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
                    <img src="./icon/user.png" alt="Morador" width="24" height="24">
                    <strong>Morador</strong>
                    <small>Quero contratar serviços</small>
                </div>
            </label>
            <label class="tipo-opcao">
                <input type="radio" name="tipo_visual" value="sindico" onchange="tipoChange(this)">
                <div class="tipo-card">
                    <img src="./icon/build.png" alt="Síndico" width="24" height="24">
                    <strong>Síndico</strong>
                    <small>Gerencio o condomínio</small>
                </div>
            </label>
        </div>

        <form action="./controls/cadastro.act.php" method="post">

            <input type="hidden" name="tipo_usuario" id="tipo_usuario_hidden" value="morador">

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