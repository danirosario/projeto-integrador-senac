<?php
require_once('../config.php');
require_once("auth_check.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_product') {
        $name = trim($_POST['product-name'] ?? '');
        $categoryId = (int) ($_POST['category'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $minStock = (int) ($_POST['minStock'] ?? 0);

        $imageUrl = '';

        if (isset($_FILES['image-url']) && $_FILES['image-url']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image-url']['tmp_name'];
            $fileName = $_FILES['image-url']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../images/';

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imageUrl = 'images/' . $newFileName;
                } else {
                    die("Erro ao mover o arquivo.");
                }
            } else {
                die("Formato de imagem inválido. Apenas JPG, JPEG, PNG e WEBP são permitidos.");
            }
        }

        if ($name !== '' && $categoryId > 0) {
            $stmt = $conn->prepare("INSERT INTO product (Name, BasePrice, Stock, MinStock, Description, ImageURL, Category_idCategory) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdiissi", $name, $price, $stock, $minStock, $description, $imageUrl, $categoryId);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: products.php");
                exit;
            } else {
                die("Erro: " . $stmt->error);
            }
        } else {
            die("Validação falhou: Verifique se o nome e a categoria foram preenchidos.");
        }
    }

    if ($_POST['action'] === 'add_category') {
        $catName = trim($_POST['category-name'] ?? '');
        $catDesc = trim($_POST['description'] ?? '');

        if ($catName !== '') {
            $stmt = $conn->prepare("INSERT INTO category (Name, Description, isActive) VALUES (?, ?, 1)");
            $stmt->bind_param("ss", $catName, $catDesc);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: products.php");
                exit;
            } else {
                die("Erro ao inserir categoria: " . $stmt->error);
            }
        } else {
            die("Nome da categoria é obrigatório.");
        }
    }
}

// BUSCAR CATEGORIAS
$categories = $conn->query("SELECT idCategory, Name FROM category WHERE isActive = 1 ORDER BY Name");

$products = $conn->query("SELECT p.idProduct, p.Name, p.BasePrice, p.Stock, p.Description, p.ImageURL, c.Name 
                          AS CategoryName FROM product p 
                          JOIN category c ON c.idCategory = p.Category_idCategory WHERE p.isActive = 1 
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
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="products.php">Produtos</a></li>
                    <li><a href="orders.php">Pedidos</a></li>
                    <li><a href="stock.php">Estoque</a></li>
                    <li><a href="reports.php">Relatórios</a></li>
                </ul>

                <div class="perfil">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <a href="../logout.php">Logout</a>
                    <?php else: ?>
                        <a href="../login.php">Login</a>
                    <?php endif; ?>
                </div>

            </nav>
        </aside>
        <div class="content-area">
            <main class="content-main">
                <section class="registered-products">
                    <div class="section-header">
                        <h3>Produtos Cadastrados</h3>

                        <div>
                            <button type="button" class="btn btn-info" onclick="openModal('myModal2')"
                                style="margin-right: 10px;">+ Adicionar Categoria</button>
                            <button type="button" class="btn btn-info" onclick="openModal('myModal')">+ Adicionar
                                Produto</button>
                        </div>

                    </div>

                    <div class="table-responsive">
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
                                        <td><?php echo htmlspecialchars($p['Name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['CategoryName']); ?></td>
                                        <td>R$ <?php echo number_format($p['BasePrice'], 2, ',', '.'); ?></td>
                                        <td><?php echo (int) $p['Stock']; ?></td>
                                        <td class="description-text"><?php echo htmlspecialchars($p['Description']); ?></td>
                                        <td>
                                            <div class="product-actions">
                                                <a href="edit_product.php?id=<?= $p['idProduct'] ?>"
                                                    class="btn-edit">Editar</a>
                                                <a href="delete_product.php?id=<?= $p['idProduct'] ?>" class="btn-delete"
                                                    onclick="return confirm('Excluir este produto?')">Excluir</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Modal Produto -->
                <div class="modal" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" onclick="closeModal('myModal')">&times;</button>
                                <h4 class="modal-title">Adicionar Novo Produto</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="products.php" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="add_product">
                                    <div class="form-group">
                                        <label for="product-name">Nome do Produto</label>
                                        <input type="text" class="form-control" id="product-name" name="product-name"
                                            placeholder="Nome do Produto" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Categoria</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">Selecione...</option>
                                            <?php
                                            while ($cat = $categories->fetch_assoc()):
                                                ?>
                                                <option value="<?php echo (int) $cat['idCategory']; ?>">
                                                    <?php echo htmlspecialchars($cat['Name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Descrição</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            placeholder="Descrição"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Preço</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price"
                                            placeholder="Preço" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="image-url">Imagem do Produto</label>
                                        <input type="file" class="form-control" id="image-url" name="image-url"
                                            accept="image/*" />
                                    </div>
                                    <div class="form-group">
                                        <label for="stock">Quantidade em Estoque</label>
                                        <input type="number" class="form-control" id="stock" name="stock"
                                            placeholder="Quantidade em Estoque" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="minStock">Estoque Mínimo</label>
                                        <input type="number" class="form-control" id="minStock" name="minStock"
                                            placeholder="Quantidade Mínima do Estoque" required />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Adicionar Produto</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Categoria -->
                <div class="modal" id="myModal2">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">

                                <button type="button" class="close" onclick="closeModal('myModal2')">&times;</button>
                                <h4 class="modal-title">Adicionar Nova Categoria</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="products.php">
                                    <input type="hidden" name="action" value="add_category">

                                    <div class="form-group">
                                        <label for="category-name">Nome da Categoria</label>
                                        <input type="text" class="form-control" id="category-name" name="category-name"
                                            placeholder="Nome da Categoria" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Descrição</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            placeholder="Descrição"></textarea>
                                    </div>

                                    <div class="modal-footer" style="padding: 15px 0 0 0; border-top: none;">
                                        <button type="submit" class="btn btn-success">Adicionar Categoria</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </main>

            <footer>
                <p>&copy; 2026 CriArty. Todos os direitos reservados.</p>
            </footer>
        </div>
    </div>
    <script src="../js/modal.js"></script>
</body>

</html>