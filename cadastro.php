<?php
require_once './includes/funcoes.php';
loginFeito();

include('./includes/head.php');
include('./includes/topo.php');
?>

<div class="div-auth">
    <main class="autenticar">
        <div class="div-form">
            <form action="./controls/cadastro.act.php" method="post" class="form ativar-load">
                <h1>Cadastro</h1>
                <div class="box-auth">
                    <label for="idNome">Nome</label>
                    <input type="text" name="nome" id="idNome" placeholder="Nome" required>
                </div>

                <div class="box-auth">
                    <label for="idEmail">Email</label>
                    <input type="email" name="email" id="idEmail" placeholder="Email" onkeydown="if(event.key == ' ') event.preventDefault()" required>
                </div>

                <div class="box-auth">
                    <label for="idSenha">Senha (min: 8)</label>
                    <div class="input-container">
                        <input type="password" name="senha" id="idSenha" minlength="8" onkeydown="if(event.key === ' ') event.preventDefault()" oninput="verificarSenha()" placeholder="Senha" required>
                        <img src="./icon/visibility.png" class="olho-icon" alt="Mostrar senha" onclick="toggleSenha('idSenha', this)">
                    </div>
                    <p class="texto-senha" style="color: var(--verde-musgo-medio);"></p>
                </div>

                <div class="box-auth">
                    <label for="idRptSenha">Repita senha (min: 8)</label>
                    <div class="input-container">
                        <input type="password" name="rptSenha" id="idRptSenha" minlength="8" onkeydown="if(event.key === ' ') event.preventDefault()" oninput="verificarSenha()" placeholder="Repita senha" required>
                        <img src="./icon/visibility.png" class="olho-icon" alt="Mostrar senha" onclick="toggleSenha('idRptSenha', this)">
                    </div>
                </div>

                <div class="box-auth">
                    <label for="selectType">Tipo de morador</label>
                    <select name="tipo_usuario" id="selectType">
                        <option value="" disabled selected hidden>Tipo de cadastro</option>
                        <option value="morador">Morador</option>
                        <option value="sindico">Síndico</option>
                    </select>
                </div>

                <div id="campoCnpj" class="box-auth" style="display: none;">
                    <label for="cnpjId">CNPJ do condominio:</label>
                    <input type="text" name="cnpj_condominio" id="cnpjId" placeholder="Digite o CNPJ">
                </div>

                <div class="box-btn">
                    <button type="submit" id="btnEnviar" class="btn btn-auth">Criar conta</button>
                    <a href="./login.php" class="btn btn-auth">Login</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const select = document.getElementById('selectType')
        const cnpj = document.getElementById('campoCnpj')

        select.addEventListener('change', function() {
            if (this.value === 'sindico') {
                cnpj.style.display = 'flex'
                document.getElementById('cnpjId').required = true
            } else {
                cnpj.style.display = 'none'
                document.getElementById('cnpjId').required = false
            }
        })
    </script>

    <img src="./img/banner.png" alt="" class="banner">
</div>

<?php include('./includes/rodape.php'); ?>