<?php
@session_start();

if (isset($_SESSION["mensagem"])) {

    $mensagem = $_SESSION["mensagem"];
    $tipo = $_SESSION["tipo"] ?? "";

    echo "<h2 class='msg-avisos' id='mensagem'>" . htmlspecialchars($mensagem) . "</h2>";

    unset($_SESSION["mensagem"]);
    unset($_SESSION["tipo"]);
?>
    <script>
        setTimeout(() => {
            const msg = document.getElementById("mensagem");
            if (msg) {
                msg.style.transition = "all 0.5s ease";
                msg.style.transform = "translateY(-20px)";
                msg.style.opacity = "0";
            }

            <?php if ($tipo === "desconectado") : ?>
                setTimeout(() => {
                    window.location.href = "./login.php";
                });
            <?php endif; ?>

        }, 4500);
    </script>
<?php
}
?>