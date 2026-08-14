<?php
require_once("../config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/client-styles/shop.css">
    <link rel="stylesheet" href="../css/client-styles/footer.css">
    <link rel="stylesheet" href="../css/client-styles/cart.css">
    <title>Meu Carrinho</title>
</head>

<body>
    <nav class="navbar">
        <div class="nav-logo">
            <a href="shop.php">Logo</a>
        </div>

        <ul class="nav-links">
            <li><a href="shop.php">Home</a></li>
            <li><a href="productsList.php">Produtos</a></li>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <li><a href="cart.php">Meu Carrinho</a></li>
            <?php endif; ?>
            <li><a href="#contato">Contato</a></li>
        </ul>

        <div class="perfil">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="../logout.php">Logout</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="cart-container">
        <div class="cart-title">
            <h2>Meu Carrinho</h2>
        </div>
        <div class="cart-grid">
            <ul class="list-itens">
                <li class="item-cart">
                    <span class="product-name">Camisa</span>
                    <span class="product-price">R$ 9,99</span>
                    <button class="remove">x</button>
                </li>
                <li class="item-cart">
                    <span class="product-name">Camisa</span>
                    <span class="product-price">R$ 9,99</span>
                    <button class="remove">x</button>
                </li>
            </ul>
            <div class="side">
                <div class="total-cart">
                    <strong>Total: R$ 150,00</strong>
                </div>
                <button class="finalize-order">Finalizar Compra</button>
            </div>
        </div>
    </div>

    <footer id="contato">
        <div class="footer-container">
            <!-- Coluna 1: Contato -->
            <div class="footer-column contato-block">
                <h2>Contato</h2>
                <div class="contato-content">
                    <p>Entre em contato conosco:</p>
                    <ul>
                        <li>Email: <a href="mailto:contato@criarty.com">contato@criarty.com</a></li>
                        <li>Telefone: <a href="tel:+5511999999999">(11) 99999-9999</a></li>
                    </ul>
                </div>
            </div>

            <!-- Coluna 2: Redes Sociais -->
            <div class="footer-column social-block">
                <h2>Siga-nos</h2>
                <ul>
                    <li><a href="https://www.instagram.com/criarty_personalizados?igsh=MWZubnZ1MTcxZDlqcg%3D%3D"
                            target="_blank">Instagram</a></li>
                </ul>
            </div>

            <!-- Coluna 3: Copyright e Topo (Agora integrado na linha principal) -->
            <div class="footer-column credits-block">
                <p>&copy; 2026 CriArty.<br>Todos os direitos reservados.</p>
                <a href="#" class="back-to-top">Retornar ao topo</a>
            </div>
        </div>
    </footer>

</body>

</html>