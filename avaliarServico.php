<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');
?>

<div class="filter-bar">
  <button class="filter-chip active" onclick="setFilter('todos', this)">Todos</button>
  <button class="filter-chip" onclick="setFilter('pendente', this)">Pendentes</button>
  <button class="filter-chip" onclick="setFilter('avaliado', this)">Avaliados</button>
</div>


<div class="cardsAvaliar">
  <?php
  $servicos = request(
    "contratados?select=id,hora,dia,confirmado,servicos(nome,descricao,imagem)&id_cliente=eq.$id&avaliar=eq.false",
    "GET"
  );

  if (!empty($servicos) && !isset($servicos['error'])) :

    foreach ($servicos as $servico) :
      $dia = date('d/m/Y', strtotime($servico['dia']));
  ?>
      <form action="./controls/avaliarServico.act.php" method="post" class="cardAvaliar">
        <div class="cardAvaliar-top">
          <div class="service-icon"><img src="<?php echo $servico['servicos']['imagem'] ?>" alt=""></div>
          <div class="cardAvaliar-info">
            <div class="service-name"><?php echo $servico['servicos']['nome'] ?></div>
            <div class="service-meta">Troca de disjuntor — <?php echo $dia ?></div>
          </div>
          <span class="badge done"><?php echo $servico['confirmado'] ?></span>
        </div>
        <input type="hidden" value="<?php echo $servico['servicos']['id'] ?>" name="id_servico">

        <div class="star-row">
          <input type="hidden" name="nota" class="nota-input" value="0" required>

          <span class="star" data-value="1">★</span>
          <span class="star" data-value="2">★</span>
          <span class="star" data-value="3">★</span>
          <span class="star" data-value="4">★</span>
          <span class="star" data-value="5">★</span>
          <span class="star-label">Toque para avaliar</span>
        </div>

        <textarea class="comment-areaAvaliacao" maxlength="500" placeholder="Comentário opcional" name="comentario"></textarea>
        <div class="NumCaracteres" id="char-count">0 / 500</div>
        <button type="submit" class="submit-btnAvaliacaoSER">Enviar avaliação</button>
      </form>
    <?php
    endforeach;
  else:
    ?>
    <div class="empty-state" id="emptyState">
      <div class="empty-icon">🔍</div>
      <div class="empty-title">Nenhum serviço encontrado</div>
      <div class="empty-text">Tente outro filtro ou ative a opção<br>para ver todos os serviços prestados <br> do condomínio.</div>
    </div>
  <?php
  endif;
  ?>
</div>

<script>
  document.querySelectorAll('.star-row').forEach(container => {
    const estrelas = container.querySelectorAll('.star');
    const inputOculto = container.querySelector('.nota-input');
    const label = container.querySelector('.star-label');

    estrelas.forEach(estrela => {
      estrela.addEventListener('click', function() {
        const valor = this.getAttribute('data-value');
        inputOculto.value = valor;

        label.textContent = `Nota: ${valor} / 5`;

        estrelas.forEach(s => {
          if (s.getAttribute('data-value') <= valor) {
            s.classList.add('active');
          } else {
            s.classList.remove('active');
          }
        });
      });
    });
  });
</script>

<?php include('./includes/rodape.php'); ?>