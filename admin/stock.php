<?php
require_once("../conexao.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Total de produtos ativos
$result = $conn->query("SELECT COUNT(*) AS total FROM product WHERE isActive = 1");
$totalProducts = $result->fetch_assoc()['total'];

// Total em estoque
$result = $conn->query("SELECT SUM(Stock) AS total FROM product WHERE isActive = 1");
$totalStock = $result->fetch_assoc()["total"] ?? 0;

// Busca os produtos para listar na tabela
$products = $conn->query("SELECT idProduct, Name, Stock, MinStock FROM product WHERE isActive = 1");

// REPOSIÇÃO DE ESTOQUE
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idProduct = intval($_POST['id']);
    $addedQuantity = intval($_POST['stock']);

    if ($addedQuantity > 0) {
        $stmt = $conn->prepare("UPDATE product SET Stock = Stock + ? WHERE idProduct = ?");
        $stmt->bind_param("ii", $addedQuantity, $idProduct);

        if ($stmt->execute()) {
            header("Location: stock.php?status=success");
            exit;
        } else {
            echo "Erro ao atualizar o estoque: " . $conn->error;
        }
        $stmt->close();
    }
}

// BUSCA DADOS DO PRODUTO
$productData = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT Name, Stock FROM product WHERE idProduct = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $productData = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin-styles/products.css" />
    <link rel="stylesheet" href="../css/admin-styles/stock.css" />
    <title>CriArty - Estoque</title>
</head>

<body>

    <div class="main-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo">
                <img src="../images/logo.png" alt="Logo CriArty" />
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="products.php">Produtos</a></li>
                    <li><a href="orders.php">Pedidos</a></li>
                    <li><a href="stock.php" class="active">Estoque</a></li>
                    <li><a href="reports.php">Relatórios</a></li>
                </ul>
            </nav>
        </aside>

        <!-- CONTENT AREA -->
        <div class="content-area">
            <main>
                <p class="welcome-text">Gerenciamento de Estoque</p>

                <!-- ESTOQUE -->
                <section class="stock-section">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Estoque</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($products && $products->num_rows > 0): ?>
                                <?php while ($product = $products->fetch_assoc()): ?>
                                    <?php
                                    $statusItem = ($product['Stock'] <= $product['MinStock']) ? "Baixo" : "OK";
                                    $classStatus = ($statusItem === "Baixo") ? "status-low" : "status-ok";
                                    ?>
                                    <tr class="stock-item">
                                        <td><?php echo htmlspecialchars($product['Name']); ?></td>
                                        <td><?php echo $product['Stock']; ?></td>
                                        <td>
                                            <span class="<?php echo $classStatus; ?>">
                                                <?php echo $statusItem; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="stock-actions">
                                                <a href="stock.php?id=<?= $product['idProduct'] ?>"
                                                    class="btn-restock">Repor</a>
                                                <a href="stock.php?id=<?= $product['idProduct'] ?>"
                                                    class="btn-withdraw">Retirar</a>
                                                <!--add modal para retirar do estoque-->
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Nenhum produto em estoque.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>

                <!-- HISTÓRICO DE MOVIMENTAÇÕES -->
                <div class="history-header">
                    <h2>Histórico de Movimentações</h2>
                    <span>Total de Produtos Ativos: <?php echo $totalProducts; ?></span><br>
                    <span>Total em Estoque: <?php echo $totalStock; ?></span><br><br>
                </div>
                <section class="stock-section">
                    <div class="history-content">
                        <table class="stock-table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Tipo de Movimentação</th>
                                    <th>Quantidade</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4">Nenhuma movimentação registrada.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

            </main>

            <footer>
                <p>&copy; 2026 CriArty. Todos os direitos reservados.</p>
            </footer>

        </div>
    </div>

    <!-- MODAL DE REPOSIÇÃO -->
    <?php if ($productData): ?>
        <div class="modal" id="myModal"
            style="display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="window.location.href='stock.php'">&times;</button>
                        <h4 class="modal-title">Reposiçao de Estoque</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <h2>Produto: <?= htmlspecialchars($productData['Name']) ?></h2>
                            <p>Estoque Atual: <strong><?= $productData['Stock'] ?></strong></p>
                        </div>
                        <form action="stock.php?id=<?= $id ?>" method="POST">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?= $id ?>">

                                <label for="stock">Quantidade a Adicionar:</label><br>
                                <input type="number" id="stock" name="stock" min="1" required autofocus>
                            </div>
                            <div class="form-actions">
                                <button class="btn btn-success">Salvar Reposição</button>
                                <a href="stock.php" class="btn btn-default">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="../js/modal.js"></script>
</body>

</html>