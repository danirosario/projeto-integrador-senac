const container = document.querySelector('.carrossel-container');
const btnPrev   = document.querySelector('.btn-prev');
const btnNext   = document.querySelector('.btn-next');

const scrollAmount = 224;

// Esconder as setas
function toggleArrows() {
    const currentScroll = container.scrollLeft;
    const maxScroll     = container.scrollWidth - container.clientWidth;

    if (currentScroll <= 5) {
        btnPrev.classList.add('hidden');
    } else {
        btnPrev.classList.remove('hidden');
    }

    if (currentScroll >= maxScroll - 5) {
        btnNext.classList.add('hidden');
    } else {
        btnNext.classList.remove('hidden');
    }
}

container.addEventListener('scroll', toggleArrows);
toggleArrows();

// Eventos dos botões (setas)
btnNext.addEventListener('click', () => {
    container.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
});

btnPrev.addEventListener('click', () => {
    container.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
    });
});
