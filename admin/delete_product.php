<?php
require_once('../config.php');
require_once("auth_check.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "ID Inválido!";
    exit;
}

$stmt_log = $conn->prepare("DELETE FROM stocklog WHERE Product_idProduct = ?");
$stmt_log->bind_param("i", $id);
$stmt_log->execute();
$stmt_log->close();

$stmt = $conn->prepare("DELETE FROM product WHERE idProduct = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<script> alert('Excluído com sucesso!'); window.location.href = 'products.php'; </script>";
        exit;
    } else {
        echo "<script> alert('Nenhum produto encontrado!'); window.location.href = 'products.php'; </script>";
    }
} else {
    echo "<script> alert('Erro: " . $stmt->error . "'); window.location.href = 'products.php'; </script>";
}

$stmt->close();
$conn->close();