<?php


if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

header("Location: cart.php");
exit;
