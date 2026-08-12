<?php
require_once("../config.php");

$result = $conn->query("SELECT Name, BasePrice, Description, ImageURL FROM product WHERE isActive = 1");

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$categories = $conn->query("SELECT idCategory, Name FROM category WHERE isActive = 1 ORDER BY Name");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/client-styles/about.css">
    <link rel="stylesheet" href="../css/client-styles/shop.css">
    <link rel="stylesheet" href="../css/client-styles/footer.css">
    <title>CriArty | Produtos</title>
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
                <li><a href="#contato">Contato</a></li>
            </ul>

            <div class="perfil">
                <a href="../logout.php">Logout</a>
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

            <div class="section-header">
                <h1 id="products-title">Conheça nossos produtos</h1>
                <a href="productsList.php" id="ver-mais">Ver todos os produtos</a>
            </div>
            <br>
            <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php
                    $count = 0;
                    foreach ($products as $product):
                        if ($count >= 4)
                            break;
                        $count++;
                        ?>
                        <article class="product-card">
                            <img src="<?php echo htmlspecialchars($product['ImageURL']); ?>"
                                alt="<?php echo htmlspecialchars($product['Name']); ?>">
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($product['Name']); ?></h3>
                                <h4>Descrição</h4>
                                <p><?php echo htmlspecialchars($product['Description']); ?></p>
                                <!-- <span class="price">R$ <?php echo number_format($product['BasePrice'], 2, ',', '.'); ?></span>
                                <button type="button">Comprar</button> -->
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>

            <br>
            <section class="category-section">
                <h2 id="category">Busque por categorias</h2>
                <div class="categories">
                    <?php if ($categories->num_rows > 0): ?>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <a href="productsList.php?category_id=<?php echo $category['idCategory']; ?>"
                                class="category-card-link">
                                <div class="category-card">
                                    <!-- <img src="../images/categoria_padrao.png" alt="Categoria 1"> -->
                                    <h3><?php echo htmlspecialchars($category['Name']); ?></h3>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Nenhuma categoria encontrada.</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <div class="about-content">
        <article class="aboutUs-area">
            <div class="about-title">
                <h1>Sobre Nós</h1>
            </div>
            <p id="about-us">A <strong>CriArty</strong> nasceu do desejo profundo de resgatar o valor emocional que os
                objetos do dia a
                dia podem carregar. Em um mundo cada vez mais padronizado e acelerado, a empresa foi fundada para ser um
                respiro de originalidade: um espaço onde o trabalho artesanal e o design intencional se encontram para
                dar vida a histórias reais. <br><br>

                <strong>O Motivo e a Essência</strong><br>
                O grande impulsionador da CriArty é a convicção de que nenhum presente ou item pessoal deve ser "apenas
                mais um". A marca surgiu ao perceber que as pessoas não buscam apenas produtos, mas sim formas de
                eternizar sentimentos, homenagear quem amam ou expressar sua própria identidade. Cada projeto começa com
                uma escuta atenta, entendendo que por trás de cada pedido existe uma conquista, um afeto ou uma
                celebração.<br><br>

                <strong>O Processo Criativo e o Fazer Manual</strong><br>
                Diferente da produção em massa, o processo de criação na CriArty combina precisão técnica com o capricho
                manual. A empresa equilibra inovação e dedicação artesanal, garantindo que o resultado final não seja
                apenas bonito, mas impecável em acabamento e durabilidade.<br><br>

                <strong>O Compromisso</strong><br>
                Mais do que entregar personalizados, a CriArty constrói conexões. Com transparência do primeiro
                atendimento à entrega final, o objetivo é garantir que a experiência de idealizar um produto seja tão
                especial quanto o momento de recebê-lo.
            </p>
            <br>

            <div class="about-us">

                <div class="about-us-card">
                    <h2>Missão</h2>
                    <div class="about-text">
                        <p> Transformar ideias e sentimentos em produtos exclusivos, oferecendo soluções personalizadas
                            que celebram a individualidade de cada cliente e tornam momentos comuns em memórias
                            inesquecíveis.
                        </p>
                    </div>
                </div>

                <br>

                <div class="about-us-card">
                    <h2>Visão</h2>
                    <div class="about-text">
                        <p>Ser referência no mercado de produtos personalizados, reconhecida pela excelência criativa,
                            qualidade impecável e pela capacidade de conectar pessoas através da arte e do design.
                        </p>
                    </div>

                </div>

                <br>

                <div class="about-us-card">
                    <h2>Valores</h2>
                    <div class="about-text">
                        <p><strong>Criatividade Autêntica:</strong> Valorizar o novo e o original em cada
                            detalhe.<br><br>
                            <strong>Foco no Cliente:</strong> Entender que cada pedido carrega uma história
                            única.<br><br>
                            <strong>Qualidade e Capricho:</strong> Entrega de produtos feitos com precisão e cuidado
                            artesanal.<br><br>
                            <strong>Comprometimento:</strong> Respeito a prazos e transparência em todo o processo de
                            criação.<br><br>
                            <strong>Paixão pelo que faz:</strong> Acreditar que o trabalho manual e personalizado agrega
                            valor emocional.
                        </p>
                    </div>
                </div>
            </div>
        </article>
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


    <script src="../js/slider.js"></script>
</body>

</html>