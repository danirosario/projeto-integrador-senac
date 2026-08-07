// Abre o modal recebendo o ID como parâmetro
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }
}

// Fecha o modal recebendo o ID como parâmetro
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }
}

// Configura o evento de clique fora para todos os modais da página
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});