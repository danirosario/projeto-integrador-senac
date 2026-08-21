<?php
require_once("../config.php");

$id_category = filter_input(INPUT_GET, "category_id", FILTER_VALIDATE_INT);

if ($id_category) {
    $stmt = $conn->prepare("SELECT idProduct, Name, BasePrice, Description, ImageURL FROM product WHERE isActive = 1 AND Category_idCategory = ?");
    $stmt->bind_param("i", $id_category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT idProduct, Name, BasePrice, Description, ImageURL FROM product WHERE isActive = 1");
}

$products = [];
if ($result && $result->num_rows > 0) {
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/client-styles/shop.css">
    <link rel="stylesheet" href="../css/client-styles/footer.css">
    <title>Produtos</title>
</head>

<body>
    <?php if (isset($_SESSION['cart_message'])): ?>
        <div id="cart-notification" class="cart-alert">
            <span class="cart-alert-text"><?php echo $_SESSION['cart_message']; ?></span>

            <!-- Botão de Fechar -->
            <button class="cart-alert-close" onclick="fecharNotificacao()">&times;</button>
        </div>

        <script>
            function fecharNotificacao() {
                var notification = document.getElementById('cart-notification');
                if (notification) {
                    notification.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    notification.style.opacity = '0';
                    notification.style.transform = 'translate(-50%, -20px)';
                    setTimeout(function () {
                        notification.remove();
                    }, 500);
                }
            }
            setTimeout(fecharNotificacao, 4000);
        </script>

        <?php unset($_SESSION['cart_message']); ?>
    <?php endif; ?>
    <!-- NAVBAR -->
    <header>
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
    </header>

    <!-- ÁREA DE CONTEÚDO PRINCIPAL -->
    <div class="content-area-shop">
        <main class="main-content" id="produtos">
            <div class="section-header">
                <h1 id="products-title">Produtos</h1>
                <div class="category-dropdown">
                    <select id="category-list" name="category_id" onchange="location = this.value;">
                        <option value="productsList.php"> Todas as Categorias</option>
                        <?php
                        $categories = $conn->query("SELECT idCategory, Name FROM category WHERE isActive = 1 ORDER BY Name");

                        if ($categories && $categories->num_rows > 0) {
                            while ($category = $categories->fetch_assoc()) {
                                $selected = ((int) $id_category === (int) $category['idCategory']) ? ' selected' : '';
                                echo '<option value="productsList.php?category_id=' . $category['idCategory'] . '"' . $selected . '>' . $category['Name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <article class="product-card">
                            <?php
                            $imagePath = !empty($product['ImageURL']) ? '../' . $product['ImageURL'] : '../images/slide_padrao.png';
                            ?>

                            <div class="product-image">
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo $product['Name']; ?>">
                            </div>
                            <div class="card-content">
                                <h3><?php echo $product['Name']; ?></h3>
                                <h4>Descrição</h4>
                                <p><?php echo $product['Description']; ?></p>
                                <span class="price">R$ <?php echo number_format($product['BasePrice'], 2, ',', '.'); ?></span>
                                <form action="add_to_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo (int) $product['idProduct']; ?>">
                                    <button type="submit" class="btn-comprar">Adicionar ao carrinho</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>
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
                    <li><a href="https://www.instagram.com/criarty_personalizados?igsh=MWZubnZ1MTcxZDlqcg%3D%3D"
                            target="_blank">Instagram</a></li>
                </ul>
            </div>

            <!-- Coluna 3: Copyright e Topo -->
            <div class="footer-column credits-block">
                <p>&copy; 2026 CriArty.<br>Todos os direitos reservados.</p>
                <a href="#" class="back-to-top">Retornar ao topo</a>
            </div>
        </div>
    </footer>
</body>

</html>