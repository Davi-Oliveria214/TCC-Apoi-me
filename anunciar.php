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

            <label for="idImagem">Imagem (opcional)</label>
            <input type="file" id="idImagem" name="imagem" style="display: none;">
        </section>
        <div>
            <button>Adicionar</button>
        </div>
    </form>
</main>