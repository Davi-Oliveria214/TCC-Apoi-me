document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('todos-servicos');

    let velocidade = 0.7;
    let isDown = false;
    let startX;
    let scrollLeft;

    let rodando = true;

    function duplicarCards() {
        const cards = Array.from(container.querySelectorAll('.card-servico'));

        if (cards.length === 0) return;

        for (let i = 0; i < 3; i++) {
            cards.forEach(card => {
                const clone = card.cloneNode(true);
                container.appendChild(clone);
            });
        }
    }

    function autoScroll() {
        if (rodando) {
            container.scrollLeft += velocidade;

            if (container.scrollLeft >= container.scrollWidth / 2) {
                container.scrollLeft = container.scrollLeft / 2;
            }
        }

        requestAnimationFrame(autoScroll);
    }

    function parar() {
        rodando = false;
    }

    function continuar() {
        rodando = true;
    }

    container.addEventListener('mouseenter', parar);

    container.addEventListener('mouseleave', continuar);

    container.addEventListener('mousedown', (e) => {
        isDown = true;
        container.style.cursor = 'grabbing';

        startX = e.pageX;
        scrollLeft = container.scrollLeft;

        parar();
    });

    container.addEventListener('mouseup', () => {
        isDown = false;
        container.style.cursor = 'grab';
        continuar();
    });

    container.addEventListener('mouseleave', () => {
        isDown = false;
        container.style.cursor = 'grab';
        continuar();
    });

    container.addEventListener('mousemove', (e) => {
        if (!isDown) return;

        const walk = (e.pageX - startX) * 2;
        container.scrollLeft = scrollLeft - walk;
    });

    container.addEventListener('touchstart', (e) => {
        startX = e.touches[0].pageX;
        scrollLeft = container.scrollLeft;
        parar();
    });

    container.addEventListener('touchend', continuar);

    container.addEventListener('touchmove', (e) => {
        const walk = (e.touches[0].pageX - startX) * 2;
        container.scrollLeft = scrollLeft - walk;
    });

    container.style.cursor = 'grab';

    duplicarCards();
    autoScroll();
});