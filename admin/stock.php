<?php
// Inicia a sessão
// session_start();

require_once("../config.php");

// Recupera ID do produto e ação vindos via URL (GET)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$get_action = isset($_GET['action']) ? $_GET['action'] : '';

// Total de produtos ativos
$result = $conn->query("SELECT COUNT(*) AS total FROM product WHERE isActive = 1");
$totalProducts = $result->fetch_assoc()['total'];

// Total em estoque
$result = $conn->query("SELECT SUM(Stock) AS total FROM product WHERE isActive = 1");
$totalStock = $result->fetch_assoc()["total"] ?? 0;

// Busca os produtos para listar na tabela
$products = $conn->query("SELECT idProduct, Name, Stock, MinStock FROM product WHERE isActive = 1");

// REPOSIÇÃO E RETIRADA DE ESTOQUE (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    $idProduct = isset($_POST['id']) ? intval($_POST['id']) : $id; // Garantir receber o ID via POST ou GET
    $quantity = isset($_POST['stock']) ? intval($_POST['stock']) : 0;

    // AÇÃO DE ADICIONAR ESTOQUE (REPOSIÇÃO)
    if ($action == "add") {
        $addQuantity = $quantity;
        $stmt = $conn->prepare("UPDATE product SET Stock = Stock + ? WHERE idProduct = ?");
        $stmt->bind_param("ii", $quantity, $idProduct);

        if ($stmt->execute()) {
            $reason = "entrada";
            $notes = "Entrada de produto";

            $stmt = $conn->prepare("INSERT INTO stocklog (Product_idProduct, quantityChange, reason, notes, changedAt) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiss", $idProduct, $addQuantity, $reason, $notes);
            $stmt->execute();
            header("Location: stock.php?status=success");
            exit;
        } else {
            echo "Erro ao atualizar o estoque: " . $conn->error;
        }
        $stmt->close();
    }
    // AÇÃO DE RETIRAR DO ESTOQUE
    elseif ($action == "withdraw") {
        $removedQuantity = $quantity; // Define a quantidade a ser removida

        // Verifica o estoque atual do produto
        $stmt = $conn->prepare("SELECT Stock FROM product WHERE idProduct = ?");
        $stmt->bind_param("i", $idProduct);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        // Valida se há quantidade suficiente em estoque
        if (!$product || $product['Stock'] < $removedQuantity) {
            die("Quantidade indisponível em estoque.");
        }

        // Atualiza tirando do estoque
        $stmt = $conn->prepare("UPDATE product SET Stock = Stock - ? WHERE idProduct = ?");
        $stmt->bind_param("ii", $removedQuantity, $idProduct);
        $stmt->execute();
        $stmt->close();

        // Registra a movimentação 
        // $user = $_SESSION['idUser'] ?? null; // Usuário logado (Futuro)
        $reason = "saida";
        $notes = "Retirada manual";

        // VERSÃO COM USER_ID (Para quando o sistema de login estiver pronto):
        // $stmt = $conn->prepare("INSERT INTO stocklog (Product_idProduct, quantityChange, reason, notes, changedAt, User_idUser) VALUES (?, ?, ?, ?, NOW(), ?)");
        // $stmt->bind_param("iissi", $idProduct, $removedQuantity, $reason, $notes, $user);

        // VERSÃO ATUAL (Sem User_idUser):
        $stmt = $conn->prepare("INSERT INTO stocklog (Product_idProduct, quantityChange, reason, notes, changedAt) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiss", $idProduct, $removedQuantity, $reason, $notes);
        $stmt->execute();
        $stmt->close();

        header("Location: stock.php?status=success");
        exit;
    }
}

// BUSCA DADOS DO PRODUTO PARA MODAL
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

//HISTORICO DE MOVIMENTAÇÕES (para listar na tabela)
$transactionHistory = $conn->query("SELECT p.Name, s.reason, s.quantityChange, s.changedAt
                                    FROM product p INNER JOIN stocklog s ON p.idProduct = s.Product_idProduct
                                    ORDER BY s.idStockLog DESC");

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
            <main class="content-main">
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
                                                <!-- Parâmetro action adicionado às URLs para controlar abertura do modal correto -->
                                                <a href="stock.php?id=<?= $product['idProduct'] ?>&action=restock"
                                                    class="btn-restock">Repor</a>
                                                <a href="stock.php?id=<?= $product['idProduct'] ?>&action=withdraw"
                                                    class="btn-withdraw">Retirar</a>
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
                <br><br>
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
                                <?php if ($transactionHistory && $transactionHistory->num_rows > 0): ?>
                                    <?php while ($h = $transactionHistory->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <?php echo ($h['Name']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($h['reason']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($h['quantityChange']) ?>
                                            </td>
                                            <td>
                                                <?php echo ((new DateTime($h['changedAt']))->format('d/m/y')); ?>
                                            </td>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <td colspan="4">Nenhuma movimentação registrada.</td>
                                    </tr>
                                <?php endif; ?>
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

    <!-- MODAL DE REPOSIÇÃO (Abre apenas se action == 'restock') -->
    <?php if ($productData && $get_action === 'restock'): ?>
        <div class="modal" id="myModal"
            style="display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="window.location.href='stock.php'">&times;</button>
                        <h4 class="modal-title">Reposição de Estoque</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <h2>Produto: <?= htmlspecialchars($productData['Name']) ?></h2>
                            <p>Estoque Atual: <strong><?= $productData['Stock'] ?></strong></p>
                        </div>
                        <form action="stock.php?id=<?= $id ?>" method="POST">
                            <div class="form-group">
                                <input type="hidden" name="action" value="add">

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

    <!-- MODAL PARA RETIRAR (Abre apenas se action == 'withdraw') -->
    <?php if ($productData && $get_action === 'withdraw'): ?>
        <div class="modal" id="myModal"
            style="display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="window.location.href='stock.php'">&times;</button>
                        <h4 class="modal-title">Retirar do Estoque</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <h2>Produto: <?= htmlspecialchars($productData['Name']) ?></h2>
                            <p>Estoque Atual: <strong><?= $productData['Stock'] ?></strong></p>
                        </div>
                        <form action="stock.php?id=<?= $id ?>" method="POST">
                            <div class="form-group">
                                <input type="hidden" name="action" value="withdraw">

                                <label for="stock">Quantidade a Retirar:</label><br>
                                <input type="number" id="stock" name="stock" min="1" max="<?= $productData['Stock'] ?>"
                                    required autofocus>
                            </div>
                            <div class="form-actions">
                                <button class="btn btn-success">Salvar</button>
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