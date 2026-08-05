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
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CriArty | Produtos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/client-styles/shop.css">
    <link rel="stylesheet" href="../css/client-styles/footer.css">
</head>

<body>
    <!-- NAVBAR -->
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <a href="#">Logo</a>
            </div>

            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#produtos">Produtos</a></li>
                <li><a href="about.php">Sobre</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>

            <div class="perfil">
                <a href="#">PERFIL</a>
            </div>
        </nav>


        <!-- CARROSSEL -->
        <div class="slider">
            <div class="slides">
                <!-- Radio Buttons -->
                <input type="radio" name="radio-btn" id="radio1" checked>
                <input type="radio" name="radio-btn" id="radio2">
                <input type="radio" name="radio-btn" id="radio3">
                <input type="radio" name="radio-btn" id="radio4">

                <!-- Slide Imagens -->
                <div class="slide-img">
                    <img src="../images/slide_padrao.png" alt="imagem 1">
                </div>
                <div class="slide-img">
                    <img src="../images/slide_padrao.png" alt="imagem 2">
                </div>
                <div class="slide-img">
                    <img src="../images/slide_padrao.png" alt="imagem 3">
                </div>
                <div class="slide-img">
                    <img src="../images/slide_padrao.png" alt="imagem 4">
                </div>

                <!-- NAVIGATION AUTO -->
                <div class="navigation-auto">
                    <div class="auto-btn1"></div>
                    <div class="auto-btn2"></div>
                    <div class="auto-btn3"></div>
                    <div class="auto-btn4"></div>
                </div>
            </div>

            <!-- MANUAL NAVIGATION -->
            <div class="manual-navigation">
                <label for="radio1" class="manual-btn"></label>
                <label for="radio2" class="manual-btn"></label>
                <label for="radio3" class="manual-btn"></label>
                <label for="radio4" class="manual-btn"></label>
            </div>
        </div>

    </header>

    <!-- ÁREA DE CONTEÚDO PRINCIPAL -->
    <div class="content-area">
        <main class="main-content" id="produtos">
            <h1 id="products-title">Produtos</h1>
            <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <article class="product-card">
                            <img src="<?php echo ($product['ImageURL']); ?>" alt="<?php echo ($product['Name']); ?>">
                            <div class="card-content">
                                <h3>
                                    <?php echo ($product['Name']); ?>
                                </h3>
                                <h4>Descrição</h4>
                                <p>
                                    <?php echo ($product['Description']); ?>
                                </p>
                                <span class="price">R$
                                    <?php echo number_format($product['BasePrice'], 2, ',', '.'); ?>
                                </span>
                                <button type="button">Comprar</button>
                            </div>
                        </article><br>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?><br><br>

            <h2>Categorias</h2>
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


    <script src="../js/slider.js"></script>
</body>

</html>