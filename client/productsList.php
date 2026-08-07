<?php
require_once("../config.php");

$result = $conn->query("SELECT Name, BasePrice, Description, ImageURL FROM product WHERE isActive = 1");

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
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
    <title>Document</title>
</head>

<body>
    <!-- NAVBAR -->
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <a href="#">Logo</a>
            </div>

            <ul class="nav-links">
                <li><a href="shop.php">Home</a></li>
                <li><a href="productsList.php">Produtos</a></li>
                <li><a href="about.php">Sobre</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>

            <div class="perfil">
                <a href="logout.php">Logout</a>
            </div>
        </nav>

    </header>

    <!-- ÁREA DE CONTEÚDO PRINCIPAL -->
    <div class="content-area">
        <main class="main-content" id="produtos">
            <h1 id="products-title">Produtos</h1>

            <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php

                    foreach ($products as $product):

                        ?>
                        <article class="product-card">
                            <img src="<?php echo htmlspecialchars($product['ImageURL']); ?>"
                                alt="<?php echo htmlspecialchars($product['Name']); ?>">
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($product['Name']); ?></h3>
                                <h4>Descrição</h4>
                                <p><?php echo htmlspecialchars($product['Description']); ?></p>
                                <span class="price">R$ <?php echo number_format($product['BasePrice'], 2, ',', '.'); ?></span>
                                <button type="button">Comprar</button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>

            <br>
            <button type="button" id="load-more-btn">Carregar mais produtos</button>
        </main>
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
                    <li><a href="https://facebook.com" target="_blank">Facebook</a></li>
                    <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
                    <li><a href="https://twitter.com" target="_blank">Twitter</a></li>
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