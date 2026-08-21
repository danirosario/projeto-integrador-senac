<?php


$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($product_id && isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

header("Location: cart.php");
exit;
