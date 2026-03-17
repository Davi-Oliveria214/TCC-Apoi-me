// Carrossel infinito
const container = document.getElementById('todos-servicos');
let velocidade = 0.3;
let animação;

async function abastecerCarrossel() {
    const cardsAtuais = Array.from(container.querySelectorAll('.card-servico'));
    const ids = cardsAtuais.map(c => c.dataset.id).join(',');

    try {
        const resposta = await fetch(`get_proximo_servico.php?ignore=${ids}`);
        const novoCardHtml = await resposta.text();

        if (novoCardHtml.trim() !== "") {
            container.insertAdjacentHTML('beforeend', novoCardHtml);
        }
    } catch (e) {
        console.error("Erro ao atualizar carrossel:", e);
    }
}

function mover() {
    container.scrollLeft += velocidade;

    const primeiroCard = container.firstElementChild;

    if (primeiroCard) {
        if (container.scrollLeft >= (primeiroCard.offsetWidth + 20)) {
            container.appendChild(primeiroCard);

            container.scrollLeft -= (primeiroCard.offsetWidth + 20);
        }
    }

    animação = requestAnimationFrame(mover);
}

mover();

container.addEventListener('mouseenter', () => cancelAnimationFrame(animação));
container.addEventListener('mouseleave', () => {
    cancelAnimationFrame(animação);
    mover();
});