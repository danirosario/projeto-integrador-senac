function openModal() {
    document.getElementById('myModal').classList.add('show');
    document.body.classList.add('modal-open');
}

// Fecha o modal: remove a classe "show" e libera o scroll da página
function closeModal() {
    document.getElementById('myModal').classList.remove('show');
    document.body.classList.remove('modal-open');
}

// Fecha o modal ao clicar fora do conteúdo (na área escurecida)
document.getElementById('myModal').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
});