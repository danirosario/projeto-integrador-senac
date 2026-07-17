<?php
require_once('../conexao.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "ID Inválido!";
    exit;
}

$stmt = $conn->prepare("DELETE FROM product WHERE idProduct = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<script> alert('Excluido com sucesso!'); window.location.href = 'products.php'; </script>";
        exit;
    } else {
        echo "<script> alert('Nenhum produto encontrado!'); window.location.href = 'products.php'; </script>";
    }
} else {
    echo "<script> alert('Erro: ' . $stmt->error); window.location.href = 'products.php'; </script>";
}

$stmt->close();
$conn->close();

// affected_rows serve para retornar o número de linhas que foram afetadas pela instrução SQL executada anteriormente
// adicionado para verificar se a exclusão realmente ocorreu no banco de dados, impedindo falsos sucessos se o ID não existe.