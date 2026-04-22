<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="main-add">
    
</main>

<script>
    const imageInput = document.getElementById('idImagem');
    const preview = document.getElementById('preview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(file);
        }
    });
</script>

<?php include("./includes/rodape.php"); ?>