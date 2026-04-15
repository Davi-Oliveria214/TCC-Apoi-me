<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<div class="filter-bar">
  <button class="filter-chip active" onclick="setFilter('todos', this)">Todos</button>
  <button class="filter-chip" onclick="setFilter('pendente', this)">Pendentes</button>
  <button class="filter-chip" onclick="setFilter('avaliado', this)">Avaliados</button>

</div>

<div class="cardsAvaliar">

  <div class="cardAvaliar">
    <div class="cardAvaliar-top">
      <div class="service-icon" style="background:#FFF3CD">⚡</div>
      <div class="cardAvaliar-info">
        <div class="service-name">Manutenção Elétrica</div>
        <div class="service-meta">Troca de disjuntor — 08/abr/2025</div>
      </div>
      <span class="badge done">Concluído</span>
    </div>

    <div class="star-row">
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star-label">Toque para avaliar</span>
    </div>

    <textarea class="comment-areaAvaliacao" maxlength="350" placeholder="Comentário opcional"></textarea>
    <div class="NumCaracteres" id="char-count">0 / 500</div>
    <button class="submit-btnAvaliacaoSER">Enviar avaliação</button>
  </div>

  <div class="cardAvaliar">
    <div class="cardAvaliar-top">
      <div class="service-icon" style="background:#D6EAF8">💧</div>
      <div class="cardAvaliar-info">
        <div class="service-name">Limpeza de Caixa d'Água</div>
        <div class="service-meta">Limpeza semestral — 02/abr/2025</div>
      </div>
      <span class="badge done">Concluído</span>
    </div>

    <div class="star-row">
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star">★</span>
      <span class="star-label">Toque para avaliar</span>
    </div>

    <textarea class="comment-areaAvaliacao" maxlength="350" placeholder="Comentário opcional"></textarea>
    <div class="NumCaracteres" id="char-count">0 / 500</div>
    <button class="submit-btnAvaliacaoSER">Enviar avaliação</button>
  </div>
</div>


</div>

<div class="empty-state" id="emptyState">
  <div class="empty-icon">🔍</div>
  <div class="empty-title">Nenhum serviço encontrado</div>
  <div class="empty-text">Tente outro filtro ou ative a opção<br>para ver todos os serviços prestados <br> do condomínio.</div>
</div>