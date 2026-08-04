<?php
require_once("../conexao.php");

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
                <li><a href="#produtos">Produtos</a></li>
                <li><a href="about.php">Sobre</a></li>
                <li><a href="#contato">Contato</a></li>

                <li><a href="">PERFIL</a></li>
            </ul>
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
            <?php endif; ?>

            <h2>Categorias</h2>
        </main>
    </div>
    <footer>
        <p>&copy; 2026 CriArty. Todos os direitos reservados.</p>
        <a href="#">Retornar ao topo</a>
    </footer>
    <script src="../js/slider.js"></script>
</body>

</html>