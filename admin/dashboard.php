<?php
require_once("../config.php");
require_once("auth_check.php");

// Total de produtos ativos
$result = $conn->query("SELECT COUNT(*) AS total FROM product WHERE isActive = 1");
$totalProducts = $result->fetch_assoc()['total'];

// Total de pedidos
$result = $conn->query("SELECT COUNT(*) AS total FROM `order`");
$totalOrders = $result->fetch_assoc()['total'];

// Soma de todas as unidades em estoque
$result = $conn->query("SELECT SUM(Stock) AS total FROM product WHERE isActive = 1");
$totalStock = $result->fetch_assoc()['total'];

// Receita total 
$result = $conn->query("SELECT SUM(TotalAmount) AS total FROM `order` WHERE PaymentStatus = 'pago'");
$totalRevenue = $result->fetch_assoc()['total'];

// Últimos 5 pedidos (mais recentes primeiro), com nome do cliente
$recentOrders = $conn->query("SELECT o.idOrder, o.OrderDate, o.TotalAmount, o.Status, c.Name 
    AS CustomerName 
    FROM `order` o 
    INNER JOIN customer c ON c.idCustomer = o.Customer_idCustomer
    ORDER BY o.OrderDate DESC
    LIMIT 5
");

// Produtos com estoque baixo, os 5 produtos com menor quantidade em estoque, considerando apenas produtos ativos
$lowStockProducts = $conn->query("SELECT idProduct, Name, Stock, MinStock FROM product
    WHERE isActive = 1 AND Stock <= MinStock
    ORDER BY Stock ASC LIMIT 5");
?>

<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/admin-styles/dashboard.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <title>CriArty - Dashboard</title>
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

    <!-- CONTENT AREA -->
    <div class="content-area">
      <main class="content-main">
        <p class="welcome-text">
          Bem-vindo ao painel de administração do CriArty! Aqui você pode
          gerenciar seus produtos, pedidos e estoque.
        </p>

        <!-- ESTATÍSTICAS RÁPIDAS -->
        <section class="stats-section">
          <div class="container-estatisticas">
            <div class="stat-card">
              <span class="stat-number"><?php echo (int) $totalProducts; ?></span>
              <p class="stat-label">Produtos</p>
            </div>
            <div class="stat-card">
              <span class="stat-number"><?php echo (int) $totalOrders; ?></span>
              <p class="stat-label">Pedidos</p>
            </div>
            <div class="stat-card">
              <span class="stat-number"><?php echo (int) $totalStock; ?></span>
              <p class="stat-label">Itens em Estoque</p>
            </div>
            <div class="stat-card">
              <span class="stat-number">R$ <?php echo number_format($totalRevenue, 2, ',', '.'); ?></span>
              <p class="stat-label">Receita Total</p>
            </div>
          </div>
        </section>

        <!-- GRÁFICOS E RELATÓRIOS -->
        <section class="charts-section">
          <div class="chart-card">
            <h3>Últimos Pedidos</h3>
            <ul class="dashboard-list">
              <?php if ($recentOrders->num_rows === 0): ?>
                <li class="dashboard-list-empty">Nenhum pedido registrado ainda.</li>
              <?php else: ?>
                <?php while ($order = $recentOrders->fetch_assoc()): ?>
                  <li class="dashboard-list-item">
                    <span class="item-title">#<?php echo $order['idOrder']; ?> -
                      <?php echo htmlspecialchars($order['CustomerName']); ?></span>
                    <span class="item-subtext">
                      <?php echo date('d/m/Y', strtotime($order['OrderDate'])); ?>
                      · R$ <?php echo number_format($order['TotalAmount'], 2, ',', '.'); ?>
                      · <?php echo htmlspecialchars($order['Status']); ?>
                    </span>
                  </li>
                <?php endwhile; ?>
              <?php endif; ?>
            </ul>
          </div>
          <div class="chart-card">
            <h3>Estoque Baixo</h3>
            <ul class="dashboard-list">
              <?php if ($lowStockProducts->num_rows === 0): ?>
                <li class="dashboard-list-empty">Nenhum produto com estoque baixo.</li>
              <?php else: ?>
                <?php while ($product = $lowStockProducts->fetch_assoc()): ?>
                  <li class="dashboard-list-item">
                    <span class="item-title"><?php echo htmlspecialchars($product['Name']); ?></span>
                    <span class="item-subtext">
                      <?php echo (int) $product['Stock']; ?> em estoque
                      (mínimo: <?php echo (int) $product['MinStock']; ?>)
                    </span>
                  </li>
                <?php endwhile; ?>
              <?php endif; ?>
            </ul>
          </div>
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