<?php
require_once('../conexao.php');

// Total de pedidos do mês
$result = $conn->query("SELECT COUNT(*) AS total FROM `order` 
                        WHERE MONTH(OrderDate) = MONTH(CURRENT_DATE()) AND YEAR(OrderDate) = YEAR(CURRENT_DATE())");
$totalData = $result->fetch_assoc();
$totalOrdersMonth = $totalData['total'] ?? 0;

// Faturamento do mês
$resultSum = $conn->query("SELECT SUM(TotalAmount) AS totalValor FROM `order` 
                           WHERE MONTH(OrderDate) = MONTH(CURRENT_DATE()) AND YEAR(OrderDate) = YEAR(CURRENT_DATE())");
$sumData = $resultSum->fetch_assoc();
$totalRevenueMonth = $sumData['totalValor'] ?? 0;

// Produto com mais estoque
$topStockQuery = $conn->query("SELECT Name, Stock FROM product WHERE isActive = 1 ORDER BY Stock DESC LIMIT 5");

// Últimos 6 meses de faturamento
$sixMonthsRevenue = $conn->query("SELECT DATE_FORMAT(OrderDate, '%Y-%m') AS Month, 
                                SUM(TotalAmount) AS TotalRevenue FROM `order` WHERE OrderDate >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
                                GROUP BY Month ORDER BY Month DESC");

// CURDATE() retorna a data atual do servidor.
// DATE_SUB(..., INTERVAL 6 MONTH) subtrai 6 meses dessa data, 
// garantindo que apenas os pedidos dos últimos 6 meses sejam considerados.
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin-styles/reports.css" />
    <title>Cryarty - Relatórios</title>
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
                    <li><a href="stock.php">Estoque</a></li>
                    <li><a href="reports.php" class="active">Relatórios</a></li>
                </ul>
            </nav>
        </aside>

        <!-- CONTENT AREA -->
        <div class="content-area">
            <main>
                <p class="welcome-text">Relatórios mensais</p>

                <div class="reports-grid">
                    <!-- Bloco 1: Pedidos -->
                    <div class="container-reports">
                        <span class="stat-number"><?php echo $totalOrdersMonth; ?></span>
                        <p class="stat-label">Pedidos do mês</p>
                    </div>

                    <!-- Bloco 2: Faturamento -->
                    <div class="container-reports">
                        <span class="stat-number">R$
                            <?php echo number_format($totalRevenueMonth, 2, ',', '.'); ?></span>
                        <p class="stat-label">Faturamento do mês</p>
                    </div>
                </div>

                <div class="reports-grid">
                    <!-- Bloco 3: Estoque -->
                    <div class="container-reports">
                        <h3 class="stat-label-top">Produto com mais estoque</h3>
                        <div class="product-info-row">
                            <?php while ($topProductData = $topStockQuery->fetch_assoc()) { ?>
                                <div class="product-item">
                                    <p class="stat-product-name"><?php echo htmlspecialchars($topProductData['Name']); ?>
                                    </p>
                                    <span class="stat-subtext"><?php echo $topProductData['Stock']; ?> em estoque</span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Bloco 4: Histórico -->
                    <div class="container-reports">
                        <h3 class="stat-label">Últimos 6 meses</h3>
                        <ul class="revenue-history-list">
                            <?php
                            if ($sixMonthsRevenue && $sixMonthsRevenue->num_rows > 0) {
                                while ($row = $sixMonthsRevenue->fetch_assoc()) {
                                    // Formatar o mês para BR (MM/YYYY)
                                    $dateObj = DateTime::createFromFormat('Y-m', $row['Month']);
                                    $formattedMonth = $dateObj ? $dateObj->format('m/Y') : $row['Month'];
                                    ?>
                                    <li class="history-row">
                                        <span class="month-label"><?php echo $formattedMonth; ?></span>
                                        <span class="month-revenue">R$
                                            <?php echo number_format($row['TotalRevenue'], 2, ',', '.'); ?></span>
                                    </li>
                                    <?php
                                }
                            } else {
                                echo "<p>Nenhum faturamento registrado.</p>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </main>

            <!-- FOOTER -->
            <footer>
                <p>&copy; 2026 CriArty. Todos os direitos reservados.</p>
            </footer>
        </div>
    </div>
</body>

</html>
