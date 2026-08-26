<?php
require_once("../config.php");

if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Faça login na sua conta ou cadastre-se para adicionar os produtos ao carrinho');
        window.history.back();
    </script>";
    exit;
}

$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

if ($product_id) {
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Se o produto já estiver no carrinho, incrementa a quantidade dele em 1
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        // Se for a primeira vez que o produto é adicionado, define a quantidade inicial como 1
        $_SESSION['cart'][$product_id] = 1;
    }
    
    $_SESSION['cart_message'] = "Produto adicionado ao carrinho!";

    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    } 
}

header("Location: shop.php?error=invalid_id");
exit;
