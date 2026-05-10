<?php
// ============================================================
// util/avisos.php — Sistema de notificações toast | Apoie.me
// Usado em praticamente todas as páginas via include em topo.php
// ============================================================
@session_start();

if (isset($_SESSION["mensagem"])):
    $mensagem = $_SESSION["mensagem"];
    $tipo     = $_SESSION["tipo"] ?? "info";

    // Define ícone SVG conforme o tipo
    $icones = [
        "sucesso"     => '<polyline points="20,6 9,17 4,12"/>',
        "erro"        => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        "aviso"       => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        "info"        => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        "desconectado" => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/>',
    ];

    $icone = $icones[$tipo] ?? $icones["info"];

    // Mantém compatibilidade: classe antiga para quem usa .msg-avisos no CSS antigo
    $classeLegacy = "msg-avisos";

    unset($_SESSION["mensagem"]);
    unset($_SESSION["tipo"]);

    // Duração do toast em ms (desconectado some mais rápido para redirecionar)
    $duracao = ($tipo === "desconectado") ? 3500 : 4500;
?>

    <!-- ===== TOAST DE AVISO ===== -->
    <div class="av-toast av-toast--<?php echo htmlspecialchars($tipo) ?>" id="av-toast" role="alert" aria-live="assertive">
        <div class="av-toast-icone">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <?php echo $icone ?>
            </svg>
        </div>
        <div class="av-toast-corpo">
            <span class="av-toast-msg <?php echo $classeLegacy ?>" id="mensagem">
                <?php echo htmlspecialchars($mensagem) ?>
            </span>
        </div>
        <button class="av-toast-fechar" onclick="fecharToast()" aria-label="Fechar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
        <div class="av-toast-barra" id="av-barra"></div>
    </div>

    <script>
        (function() {
            const toast = document.getElementById('av-toast');
            const barra = document.getElementById('av-barra');
            const duracao = <?php echo $duracao ?>;
            const redirect = <?php echo ($tipo === 'desconectado') ? 'true' : 'false' ?>;

            // Anima entrada
            requestAnimationFrame(() => {
                toast.classList.add('av-toast--visivel');
                barra.style.transition = `width ${duracao}ms linear`;
                barra.style.width = '0%';
            });

            // Timer de saída
            const timer = setTimeout(() => {
                sairToast();
            }, duracao);

            window.fecharToast = function() {
                clearTimeout(timer);
                sairToast();
            };

            function sairToast() {
                toast.classList.remove('av-toast--visivel');
                toast.classList.add('av-toast--saindo');
                setTimeout(() => {
                    toast.remove();
                    <?php if ($tipo === 'desconectado'): ?>
                        window.location.href = './login.php';
                    <?php endif; ?>
                }, 400);
            }
        })();
    </script>

<?php
endif;
?>