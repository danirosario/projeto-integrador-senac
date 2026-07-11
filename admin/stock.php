<?php
require_once("../conexao.php");

// Total de produtos ativos
$result = $conn->query("SELECT COUNT(*) AS total FROM product WHERE isActive = 1");
$totalProducts = $result->fetch_assoc()['total'];

// Total em estoque (somando todas as unidades em estoque)
$result = $conn->query("SELECT SUM(Stock) AS total FROM product WHERE isActive = 1");
$totalStock = $result->fetch_assoc()["total"] ?? 0; // Evita nulo se não houver produtos

// Busca os produtos para listar na tabela
$products = $conn->query("SELECT Name, Stock, MinStock FROM product WHERE isActive = 1");

?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
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
                <p class="welcome-text">
                    Gerenciamento de Estoque
                </p>

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
                                    // Determina o status do produto com base no estoque e no estoque mínimo
                                    $statusItem = ($product['Stock'] <= $product['MinStock']) ? "Baixo" : "OK";
                                    // Define a classe CSS com base no status do produto
                                    $classStatus = ($statusItem === "Baixo") ? "status-low" : "status-ok";
                                    ?>
                                    <tr class="stock-item">
                                        <td><?php echo htmlspecialchars($product['Name']); ?></td>
                                        <td><?php echo $product['Stock'];                  ?></td>
                                        <td>
                                            <span class="<?php echo $classStatus; ?>">
                                                <?php echo $statusItem; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="stock-actions">
                                                <button class="btn-restock">Repor   </button>
                                                <button class="btn-withdraw">Retirar</button>
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
            </main>

            <!-- FOOTER -->
            <footer>
                <p>&copy; 2026 CriArty. Todos os direitos reservados.</p>
            </footer>

        </div>
    </div>
</body>

</html>