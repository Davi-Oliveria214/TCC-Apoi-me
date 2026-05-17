<?php
@session_start();
$etapa = $_GET['etapa'] ?? 'enviar';

$email_url    = $_GET['email'] ?? '';
$codigo_url   = $_GET['codigo'] ?? '';
$tipo_codigo  = $_GET['tipo_codigo'] ?? $_SESSION['tipo_codigo'] ?? '';
$novo_email   = $_GET['novo_email'] ?? $_SESSION['novo_email']  ?? '';
$tipo_envio   = $_GET['tipo_envio'] ?? '';

if (!empty($email_url) && $etapa === 'enviar') {
    $etapa = 'codigo';
}

if ($etapa === 'codigo') {
    if (!empty($email_url))   $_SESSION['email_verificar'] = $email_url;
    if (!empty($tipo_codigo)) $_SESSION['tipo_codigo'] = $tipo_codigo;
    if (!empty($novo_email))  $_SESSION['novo_email'] = $novo_email;
}

if ($etapa === 'senha' && !isset($_SESSION['email_reset_aprovado'])) {
    $_SESSION['mensagem'] = 'Acesso inválido.';
    $_SESSION['tipo']     = 'erro';
    header('Location: ./login.php');
    exit;
}

$titulos = [
    'enviar' => 'Apoie.me — Enviar código',
    'aviso'  => 'Apoie.me — Verifique seu e-mail',
    'codigo' => 'Apoie.me — Confirmar código',
    'senha'  => 'Apoie.me — Nova senha',
];

$titulo_pag = $titulos[$etapa] ?? 'Apoie.me';

include './includes/head.php';
include './includes/topo.php';
?>

<div class="va-wrapper">

    <div class="va-visual">
        <div class="va-visual-bg"></div>

        <div class="va-visual-topo"></div>

        <div class="va-visual-rodape">
            <?php if ($etapa === 'enviar'): ?>
                <p class="va-visual-subtag">Recuperação de acesso</p>
                <h2>Precisa de ajuda<br>para <em>entrar?</em></h2>
                <p>Informe seu e-mail e enviaremos um código seguro para você recuperar o acesso.</p>

            <?php elseif ($etapa === 'aviso'): ?>
                <p class="va-visual-subtag">E-mail enviado</p>
                <h2>Verifique sua<br><em>caixa de entrada</em></h2>
                <p>O código expira em 15 minutos. Não encontrou? Cheque o spam.</p>

            <?php elseif ($etapa === 'codigo'): ?>
                <p class="va-visual-subtag">Verificação segura</p>
                <h2>Insira o código<br><em>recebido</em></h2>
                <p>O código de 6 dígitos foi enviado para o e-mail cadastrado. Ele expira em 15 minutos.</p>

            <?php elseif ($etapa === 'senha'): ?>
                <p class="va-visual-subtag">Quase lá!</p>
                <h2>Crie uma nova<br><em>senha segura</em></h2>
                <p>Use pelo menos 8 caracteres, combinando letras maiúsculas, minúsculas e números.</p>
            <?php endif; ?>

            <div class="va-progresso">
                <?php
                $passos = ['enviar', 'aviso', 'codigo', 'senha'];
                $atual  = array_search($etapa, $passos);
                foreach ($passos as $i => $p):
                ?>
                    <div class="va-passo <?php echo ($i <= $atual) ? 'va-passo--ativo' : '' ?>
                                         <?php echo ($i  < $atual) ? 'va-passo--concluido' : '' ?>">
                        <?php if ($i < $atual): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20,6 9,17 4,12" />
                            </svg>
                        <?php else: ?>
                            <?php echo $i + 1 ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="va-form-painel">
        <div class="va-form-wrap">

            <?php if ($etapa === 'enviar'): ?>

                <span class="va-subtag">Passo 1 de 4</span>
                <h1>Enviar código</h1>
                <p class="va-desc">Informe seu e-mail e escolha o motivo para receber o código de verificação.</p>

                <form action="./controls/enviar_recuperacao.act.php" method="post" class="va-form ativar-load">

                    <div class="va-campo">
                        <label for="va-email">E-mail</label>
                        <div class="va-campo-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            <input type="email" name="email" id="va-email"
                                placeholder="seu@email.com"
                                onkeydown="if(event.key===' ')event.preventDefault()"
                                required autocomplete="email">
                        </div>
                    </div>

                    <div class="va-campo">
                        <label for="va-tipo">Motivo</label>
                        <div class="va-campo-wrap va-campo-wrap--select">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <select name="categoria" id="va-tipo" required>
                                <option value="" disabled <?php echo empty($tipo_envio) ? 'selected' : '' ?> hidden>Selecione o motivo</option>
                                <option value="redefinir" <?php echo ($tipo_envio === 'redefinir') ? 'selected' : '' ?>>Redefinir senha</option>
                                <option value="validar" <?php echo ($tipo_envio === 'validar')   ? 'selected' : '' ?>>Validar e-mail</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="va-btn-principal">
                        Enviar código por e-mail
                    </button>

                </form>

                <p class="va-footer-link">Lembrou a senha? <a href="./login.php">Fazer login</a></p>

            <?php elseif ($etapa === 'aviso'): ?>

                <div class="va-icone-central">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                </div>

                <span class="va-subtag">Passo 2 de 4</span>
                <h1>E-mail enviado!</h1>
                <p class="va-desc">Enviamos um código e um link de verificação para o endereço cadastrado. <strong>Ele expira em 15 minutos.</strong></p>

                <div class="va-passos-lista">
                    <div class="va-passo-item">
                        <div class="va-passo-num">1</div>
                        <p>Abra o e-mail enviado pelo <strong>Apoie.me</strong> na sua caixa de entrada.</p>
                    </div>
                    <div class="va-passo-item">
                        <div class="va-passo-num">2</div>
                        <p>Clique no botão do e-mail ou copie o código de 6 dígitos.</p>
                    </div>
                    <div class="va-passo-item">
                        <div class="va-passo-num">3</div>
                        <p>Insira o código na página de verificação e siga as instruções.</p>
                    </div>
                </div>

                <div class="va-aviso-alerta">
                    Não encontrou? Verifique a pasta de <strong>spam</strong> ou lixo eletrônico.
                </div>

                <div class="va-aviso-acoes">
                    <a href="./verificar_acesso.php?etapa=enviar" class="va-btn-secundario">Reenviar código</a>
                    <a href="./login.php" class="va-btn-principal">Ir para o Login</a>
                </div>

            <?php elseif ($etapa === 'codigo'): ?>

                <span class="va-subtag">Passo 3 de 4</span>
                <h1>Confirmar código</h1>
                <p class="va-desc">Digite o código de 6 dígitos enviado para o seu e-mail.</p>

                <form action="./controls/verificar.act.php" method="post" class="va-form ativar-load">

                    <input type="hidden" name="tipo_codigo" value="<?php echo htmlspecialchars($tipo_codigo) ?>">
                    <input type="hidden" name="novo_email" value="<?php echo htmlspecialchars($novo_email) ?>">
                    <input type="hidden" name="email_recuperar" value="<?php echo htmlspecialchars($email_url) ?>">

                    <div class="va-codigo-label">Código de verificação</div>
                    <div class="va-codigo-grid" id="va-codigo-grid">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <input class="va-codigo-box"
                                type="text"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="<?php echo $i === 0 ? 'one-time-code' : 'off' ?>"
                                <?php echo (!empty($codigo_url) && isset($codigo_url[$i])) ? 'value="' . htmlspecialchars($codigo_url[$i]) . '"' : '' ?>>
                        <?php endfor; ?>
                    </div>

                    <input type="hidden" name="codigo" id="va-codigo-hidden"
                        value="<?php echo htmlspecialchars($codigo_url) ?>">

                    <div class="va-timer" id="va-timer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                        <span id="va-timer-txt">15:00</span> restantes
                    </div>

                    <button type="submit" class="va-btn-principal" id="va-btn-verificar" disabled style="opacity:0.5;">
                        Verificar código
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </button>

                </form>

                <p class="va-footer-link">
                    Código expirado?
                    <a href="./verificar_acesso.php?etapa=enviar<?php echo !empty($tipo_codigo) ? '&tipo_envio=' . $tipo_codigo : '' ?>">
                        Solicitar novo
                    </a>
                </p>

            <?php elseif ($etapa === 'senha'): ?>

                <span class="va-subtag">Passo 4 de 4</span>
                <h1>Criar nova senha</h1>
                <p class="va-desc">
                    Definindo nova senha para:<br>
                    <strong><?php echo htmlspecialchars($_SESSION['email_reset_aprovado']) ?></strong>
                </p>

                <form action="./controls/atualizar_senha.php" method="post" class="va-form ativar-load">

                    <div class="va-campo">
                        <label for="va-senha">Nova senha</label>
                        <div class="va-campo-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" name="senha" id="va-senha" minlength="8"
                                onkeydown="if(event.key===' ')event.preventDefault()"
                                oninput="verificarSenha(); checarForca(this.value)"
                                placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
                            <button type="button" class="va-olho" onclick="toggleSenha('olho-senha', this)" aria-label="Mostrar senha">
                                <img id="olho-senha" src="./icon/visibility.png" alt="Mostrar">
                            </button>
                        </div>

                        <div class="va-forca-barra">
                            <div class="va-forca-seg" id="vaf1"></div>
                            <div class="va-forca-seg" id="vaf2"></div>
                            <div class="va-forca-seg" id="vaf3"></div>
                            <div class="va-forca-seg" id="vaf4"></div>
                        </div>
                        <span class="va-forca-txt" id="va-forca-txt"></span>
                    </div>

                    <div class="va-campo">
                        <label for="va-rpt-senha">Confirmar senha</label>
                        <div class="va-campo-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" name="rpt_senha" id="va-rpt-senha" minlength="8"
                                onkeydown="if(event.key===' ')event.preventDefault()"
                                oninput="checarForca(this.value)"
                                placeholder="Repita a senha" required autocomplete="new-password">
                            <button type="button" class="va-olho" onclick="toggleSenha('olho-rpt-senha', this)" aria-label="Mostrar senha">
                                <img id="olho-rpt-senha" src="./icon/visibility.png" alt="Mostrar">
                            </button>
                        </div>
                        <span class="va-match-txt" id="va-match-txt"></span>
                    </div>

                    <button type="submit" class="va-btn-principal">
                        Salvar nova senha
                    </button>

                </form>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php include './includes/rodape.php'; ?>

<script>
    (function() {
        const grid = document.getElementById('va-codigo-grid');
        if (!grid) return;

        const boxes = grid.querySelectorAll('.va-codigo-box');
        const hidden = document.getElementById('va-codigo-hidden');
        const btnVerificar = document.getElementById('va-btn-verificar');

        function sync() {
            const val = [...boxes].map(b => b.value).join('');
            hidden.value = val;
            const completo = val.length === 6 && /^\d{6}$/.test(val);
            if (btnVerificar) {
                btnVerificar.disabled = !completo;
                btnVerificar.style.opacity = completo ? '1' : '0.5';
            }
        }

        boxes.forEach((box, i) => {
            box.addEventListener('input', e => {
                const v = e.target.value.replace(/\D/g, '');
                box.value = v ? v[0] : '';
                if (v && i < 5) boxes[i + 1].focus();
                sync();
            });

            box.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !box.value && i > 0) {
                    boxes[i - 1].focus();
                    boxes[i - 1].value = '';
                    sync();
                }
            });

            box.addEventListener('paste', e => {
                e.preventDefault();
                const txt = (e.clipboardData || window.clipboardData)
                    .getData('text').replace(/\D/g, '').slice(0, 6);
                txt.split('').forEach((c, j) => {
                    if (boxes[j]) boxes[j].value = c;
                });
                boxes[Math.min(txt.length, 5)].focus();
                sync();
            });
        });

        if (boxes.length > 0) {
            sync();

            if (!hidden.value) {
                boxes[0].focus();
            }
        }

        /* Timer de 15 min */
        const timerEl = document.getElementById('va-timer');
        const timerTxt = document.getElementById('va-timer-txt');
        if (timerTxt) {
            let s = 15 * 60;
            const tick = setInterval(() => {
                s--;
                if (s <= 0) {
                    clearInterval(tick);
                    timerTxt.textContent = '00:00';
                    if (timerEl) timerEl.classList.add('va-timer--expirado');
                    if (btnVerificar) {
                        btnVerificar.disabled = true;
                        btnVerificar.style.opacity = '0.4';
                    }
                    return;
                }
                if (s <= 60 && timerEl) timerEl.classList.add('va-timer--urgente');
                const m = String(Math.floor(s / 60)).padStart(2, '0');
                const sec = String(s % 60).padStart(2, '0');
                timerTxt.textContent = `${m}:${sec}`;
            }, 1000);
        }
    })();

    function vaVerificarSenha() {
        const s1El = document.getElementById('va-senha');
        const s2El = document.getElementById('va-rpt-senha');
        const matchTxt = document.getElementById('va-match-txt');
        if (!s1El || !s2El) return;

        const s1 = s1El.value;
        const s2 = s2El.value;

        if (!s2) {
            if (matchTxt) matchTxt.textContent = '';
            return;
        }

        if (s1 === s2) {
            if (matchTxt) {
                matchTxt.textContent = '✓ Senhas coincidem';
                matchTxt.style.color = '#1a7a4a';
            }
        } else {
            if (matchTxt) {
                matchTxt.textContent = '✗ Senhas não coincidem';
                matchTxt.style.color = '#c0392b';
            }
        }
    }
</script>