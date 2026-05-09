<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<!-- HERO -->
<div class="hero-contato">
    <div class="hero-inner">
        <span class="subtag">Fale conosco</span>
        <h1>Estamos aqui<br>para <em>ajudar</em></h1>
        <p>Dúvidas, sugestões, elogios ou críticas — sua opinião é fundamental para melhorarmos a plataforma.</p>
    </div>
    <div class="hero-ondas">
        <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="100%" height="60">
            <path d="M0,0 C360,60 1080,0 1440,40 L1440,60 L0,60 Z" fill="#fdf6e8" />
        </svg>
    </div>
</div>

<!-- CONTEÚDO -->
<div class="main-contato">

    <!-- COLUNA INFO -->
    <div class="col-info">
        <h2>Como podemos ajudar?</h2>
        <p>Nossa equipe está disponível para responder suas perguntas e garantir a melhor experiência na plataforma.</p>

        <div class="canais">
            <a class="canal" href="mailto:apoie.me10@gmail.com">
                <div class="canal-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                </div>
                <div class="canal-txt">
                    <strong>E-mail</strong>
                    <span>apoie.me10@gmail.com</span>
                </div>
            </a>

            <div class="canal" style="cursor:default;">
                <div class="canal-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                </div>
                <div class="canal-txt">
                    <strong>Chat na plataforma</strong>
                    <span>Disponível para usuários logados</span>
                </div>
            </div>

            <div class="canal" style="cursor:default;">
                <div class="canal-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12,6 12,12 16,14" />
                    </svg>
                </div>
                <div class="canal-txt">
                    <strong>Horário de atendimento</strong>
                    <span>Segunda a sexta, das 8h às 18h</span>
                </div>
            </div>
        </div>

        <!-- FAQ mini -->
        <div class="faq-mini">
            <h3>Perguntas frequentes</h3>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-pergunta">
                    Como faço para anunciar meu serviço?
                    <svg class="faq-seta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6,9 12,15 18,9" />
                    </svg>
                </div>
                <p class="faq-resposta">Após criar sua conta, acesse "Anunciar serviço" no menu principal e preencha as informações sobre o que você oferece, seu horário e valor.</p>
            </div>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-pergunta">
                    O cadastro é gratuito?
                    <svg class="faq-seta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6,9 12,15 18,9" />
                    </svg>
                </div>
                <p class="faq-resposta">Sim! O cadastro na plataforma é 100% gratuito tanto para moradores que desejam contratar serviços quanto para prestadores.</p>
            </div>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-pergunta">
                    Como funciona a chave de acesso?
                    <svg class="faq-seta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6,9 12,15 18,9" />
                    </svg>
                </div>
                <p class="faq-resposta">A chave de acesso é fornecida pelo síndico do seu condomínio e garante que apenas moradores do mesmo condomínio utilizem a plataforma.</p>
            </div>
        </div>
    </div>

    <!-- COLUNA FORMULÁRIO -->
    <div class="col-form">
        <div id="form-area">
            <h2 class="form-titulo">Envie sua mensagem</h2>
            <p class="form-subtitulo">Escolha o tipo de contato e preencha o formulário abaixo.</p>

            <!-- Tipo de feedback -->
            <div class="tipo-feedback">
                <button class="tipo-pill ativo" onclick="selecionarTipo(this)">Dúvida</button>
                <button class="tipo-pill" onclick="selecionarTipo(this)">Sugestão</button>
                <button class="tipo-pill" onclick="selecionarTipo(this)">Elogio</button>
                <button class="tipo-pill" onclick="selecionarTipo(this)">Crítica</button>
                <button class="tipo-pill" onclick="selecionarTipo(this)">Outro</button>
            </div>

            <form action="./controls/feedback.php" method="post" id="form-contato" onsubmit="enviarForm(event)">

                <div class="campos-2col">
                    <div class="campo">
                        <label for="textNome">Nome</label>
                        <input type="text" name="nome" id="textNome" placeholder="Seu nome" required autocomplete="name">
                    </div>
                    <div class="campo">
                        <label for="textEmail">E-mail</label>
                        <input type="email" name="email" id="textEmail" placeholder="seu@email.com" required autocomplete="email">
                    </div>
                </div>

                <input type="hidden" name="tipo" id="tipo_feedback" value="Dúvida">

                <div class="campo">
                    <label for="comentarios">Mensagem</label>
                    <textarea name="comentario" id="comentarios" placeholder="Descreva sua dúvida, sugestão ou comentário em detalhes…" required maxlength="500" oninput="atualizarContador()"></textarea>
                    <div class="contador"><span id="cont">0</span>/500</div>
                </div>

                <div class="campo">
                    <label>Como você avalia sua experiência? <span style="font-weight:300; font-size:12px; color:var(--cinza-claro)">(opcional)</span></label>
                    <div class="estrelas">
                        <input type="radio" id="s5" name="nota" value="5">
                        <label for="s5" title="Excelente">★</label>
                        <input type="radio" id="s4" name="nota" value="4">
                        <label for="s4" title="Bom">★</label>
                        <input type="radio" id="s3" name="nota" value="3">
                        <label for="s3" title="Regular">★</label>
                        <input type="radio" id="s2" name="nota" value="2">
                        <label for="s2" title="Ruim">★</label>
                        <input type="radio" id="s1" name="nota" value="1">
                        <label for="s1" title="Péssimo">★</label>
                    </div>
                </div>

                <button type="submit" class="btn-enviar" id="btn-enviar">
                    Enviar mensagem
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13" />
                        <polygon points="22,2 15,22 11,13 2,9 22,2" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Estado de sucesso -->
        <div class="mensagem-sucesso" id="sucesso">
            <div class="sucesso-icone">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20,6 9,17 4,12" />
                </svg>
            </div>
            <h3>Mensagem enviada!</h3>
            <p>Obrigado pelo seu contato.<br>Nossa equipe responderá em breve pelo e-mail informado.</p>
        </div>
    </div>

</div>

<script>
    function selecionarTipo(btn) {
        document.querySelectorAll('.tipo-pill').forEach(p => p.classList.remove('ativo'));
        btn.classList.add('ativo');
        document.getElementById('tipo_feedback').value = btn.textContent;
    }

    function atualizarContador() {
        const t = document.getElementById('comentarios');
        document.getElementById('cont').textContent = t.value.length;
    }

    function toggleFaq(item) {
        const aberto = item.classList.contains('aberto');
        document.querySelectorAll('.faq-item').forEach(f => f.classList.remove('aberto'));
        if (!aberto) item.classList.add('aberto');
    }

    function enviarForm(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-enviar');
        btn.textContent = 'Enviando…';
        btn.disabled = true;

        setTimeout(() => {
            document.getElementById('form-area').style.display = 'none';
            document.getElementById('sucesso').style.display = 'block';
        }, 1200);
    }
</script>