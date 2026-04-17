<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="main-add">
    <form action="./controls/addServico.php" method="post" class="form-add" enctype="multipart/form-data">
        <h1>Criar Anúncio</h1>
        <section class="box-add">
            <div class="box-addServicos">
                <input type="text" id="idNome" name="nome" placeholder="Nome do anúncio" required>
            </div>

            <div class="box-addServicos">
                <select name="categoria" id="idCategorias" required>
                    <option value="" disabled selected hidden>Tipo do serviço</option>
                    <?php
                    $sql = request("categorias?select=*", "GET");

                    if (!empty($sql) && !isset($sql['error'])):
                        foreach ($sql as $categoria):
                    ?>
                            <option value="<?php echo $categoria['id'] ?>"><?php echo $categoria['nome'] ?></option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="box-addServicos">
                <input type="date" id="idData" name="data" placeholder="Data (Opicional)">
            </div>

            <div class="box-addServicos">
                <input type="time" id="idHorario" name="horario" placeholder="Horario (Opicional)">
            </div>

            <div class="box-addServicos">
                <textarea name="descricao" id="idDescricao" style="resize: none;" placeholder="Descricao" required></textarea>
            </div>

            <div class="box-addServicos">
                <label for="idImagem">Clique para selecionar uma imagem</label>
                <input type="file" id="idImagem" name="imagem" style="display: none;">
                <img src="" alt="Prévia da imagem" id="preview">
            </div>
        </section>

        <div><button type="submit">Adicionar</button></div>
    </form>
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