<?php
require_once("../conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {

    // Mapeamento: campo do formulário -> coluna do banco
    $name        = trim($_POST['product-name']);   // -> product.Name
    $categoryId  = (int) $_POST['category'];       // -> product.Category_idCategory
    $description = trim($_POST['description']);    // -> product.Description
    $price       = (float) $_POST['price'];        // -> product.BasePrice
    $imageUrl    = trim($_POST['image-url']);      // -> product.ImageURL
    $stock       = (int) $_POST['stock'];          // -> product.Stock

    if ($name !== '' && $categoryId > 0 && $price >= 0) {
        $stmt = $conn->prepare("INSERT INTO product (Name, BasePrice, Stock, Description, ImageURL, Category_idCategory) 
        VALUES (?, ?, ?, ?, ?, ?)");

        // Ordem:   Name  Price  Stock  Desc   ImageUrl CategoryId
        $stmt->bind_param('sdisis', $name, $price, $stock, $description, $imageUrl, $categoryId);
        $stmt->execute();
        $stmt->close();

        header('Location: products.php'); // evita reenvio do form ao dar F5
        exit;
    }
}


// BUSCAR CATEGORIAS 
$categories = $conn->query("SELECT idCategory, Name FROM category WHERE isActive = 1 ORDER BY Name");

// BUSCAR PRODUTOS CADASTRADOS (para a tabela de listagem)
$products = $conn->query("SELECT p.idProduct, p.Name, p.BasePrice, p.Stock, p.Description, c.Name 
    AS CategoryName
    FROM product p
    JOIN category c ON c.idCategory = p.Category_idCategory
    WHERE p.isActive = 1
    ORDER BY p.idProduct DESC");
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin-styles/products.css" />
    <title>CriArty - Produtos</title>
</head>

<body>

    <div class="main-container">
        <aside class="sidebar">
            <div class="logo">
                <img src="../images/logo.png" alt="Logo CriArty" />
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="products.php" class="active">Produtos</a></li>
                    <li><a href="orders.php">Pedidos</a></li>
                    <li><a href="stock.php">Estoque</a></li>
                    <li><a href="reports.php">Relatórios</a></li>
                </ul>
            </nav>
        </aside>

        <div class="content-area">
            <main>
                <p class="welcome-text">Cadastro de Produtos</p>

                <section class="products-section">
                    <div class="product-card">
                        <h3>Adicionar Novo Produto</h3>
                        <form method="POST" action="products.php">
                            <input type="hidden" name="action" value="add_product">
                            <div class="form-group">
                                <label for="product-name">Nome do Produto</label>
                                <input type="text" id="product-name" name="product-name" placeholder="Nome do Produto" required />
                            </div>

                            <div class="form-group">
                                <label for="category">Categoria</label>
                                <select id="category" name="category" required>
                                    <option value="">Selecione...</option>
                                    <?php while ($cat = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo (int) $cat['idCategory'] ?>">
                                        <?php echo htmlspecialchars($cat['Name']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <input type="text" id="description" name="description" placeholder="Descrição" required />
                            </div>

                            <div class="form-group">
                                <label for="price">Preço</label>
                                <input type="number" step="0.01" id="price" name="price" placeholder="Preço" required />
                            </div>

                            <div class="form-group">
                                <label for="image-url">URL da Imagem</label>
                                <input type="text" id="image-url" name="image-url" placeholder="URL da Imagem" />
                            </div>

                            <div class="form-group">
                                <label for="stock">Quantidade em Estoque</label>
                                <input type="number" id="stock" name="stock" placeholder="Quantidade em Estoque" required />
                            </div>

                            <button type="submit">Adicionar Produto</button>
                        </form>
                    </div>
                </section>

                <p class="welcome-text"><br>Gerenciamento de Produtos Cadastrados</p>

                <!-- TABELA -->
                <section class="registered-products">
                    <h3>Produtos Cadastrados</h3>

                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($p = $products->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($p['Name']) ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($p['CategoryName']) ?>
                                </td>
                                <td>R$
                                    <?php echo number_format($p['BasePrice'], 2, ',', '.') ?>
                                </td>
                                <td>
                                    <?php echo (int) $p['Stock'] ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($p['Description']) ?>
                                </td>
                                <td>
                                    <div class="product-actions">
                                        <a href="edit_product.php?id=<?= $p['idProduct'] ?>" class="btn-edit">Editar</a>
                                        <a href="delete_product.php?id=<?= $p['idProduct'] ?>" class="btn-delete" 
                                            onclick="return confirm('Excluir este produto?')">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </section>
            </main>

            <footer>
                <p>&copy; 2026 CriArty. Todos os direitos reservados.</p>
            </footer>
        </div>
    </div>
</body>

</html>