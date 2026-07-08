<?php
require_once("../conexao.php");

// Total de produtos ativos
$result = $conn->query("SELECT COUNT(*) AS total FROM product WHERE isActive = 1");
$totalProdutos = $result->fetch_assoc()['total'];

// Total de pedidos
$result = $conn->query("SELECT COUNT(*) AS total FROM `order`");
$totalPedidos = $result->fetch_assoc()['total'];

// Soma de todas as unidades em estoque
$result = $conn->query("SELECT COALESCE(SUM(Stock), 0) AS total FROM product WHERE isActive = 1");
$itensEstoque = $result->fetch_assoc()['total'];

// Receita total (somando só pedidos pagos)
$result = $conn->query("SELECT COALESCE(SUM(TotalAmount), 0) AS total FROM `order` WHERE PaymentStatus = 'pago'");
$receitaTotal = $result->fetch_assoc()['total'];

// Últimos 5 pedidos (mais recentes primeiro), com nome do cliente
$ultimosPedidos = $conn->query("SELECT o.idOrder, o.OrderDate, o.TotalAmount, o.Status, c.Name 
    AS CustomerName 
    FROM `order` o 
    JOIN customer c ON c.idCustomer = o.Customer_idCustomer
    ORDER BY o.OrderDate DESC
    LIMIT 5
");

// Produtos com estoque baixo (Stock <= MinStock), os mais críticos primeiro
$estoqueBaixo = $conn->query("SELECT idProduct, Name, Stock, MinStock FROM product
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
      </nav>
    </aside>

    <!-- CONTENT AREA -->
    <div class="content-area">
      <main>
        <p class="welcome-text">
          Bem-vindo ao painel de administração do CriArty! Aqui você pode
          gerenciar seus produtos, pedidos e estoque.
        </p>

        <!-- ESTATÍSTICAS RÁPIDAS -->
        <section class="stats-section">
          <div class="container-estatisticas">
            <div class="stat-card">
              <span class="stat-number"><?php echo (int) $totalProdutos; ?></span>
              <p class="stat-label">Produtos</p>
            </div>
            <div class="stat-card">
              <span class="stat-number"><?php echo (int) $totalPedidos; ?></span>
              <p class="stat-label">Pedidos</p>
            </div>
            <div class="stat-card">
              <span class="stat-number"><?php echo (int) $itensEstoque; ?></span>
              <p class="stat-label">Itens em Estoque</p>
            </div>
            <div class="stat-card">
              <span class="stat-number">R$ <?php echo number_format($receitaTotal, 2, ',', '.'); ?></span>
              <p class="stat-label">Receita Total</p>
            </div>
          </div>
        </section>

        <!-- GRÁFICOS E RELATÓRIOS -->
        <section class="charts-section">
          <div class="chart-card">
            <h3>Últimos Pedidos</h3>
            <ul class="dashboard-list">
              <?php if ($ultimosPedidos->num_rows === 0): ?>
                <li class="dashboard-list-empty">Nenhum pedido registrado ainda.</li>
              <?php else: ?>
                <?php while ($pedido = $ultimosPedidos->fetch_assoc()): ?>
                  <li class="dashboard-list-item">
                    <span class="item-title">#<?php echo $pedido['idOrder']; ?> -
                      <?php echo htmlspecialchars($pedido['CustomerName']); ?></span>
                    <span class="item-subtext">
                      <?php echo date('d/m/Y', strtotime($pedido['OrderDate'])); ?>
                      · R$ <?php echo number_format($pedido['TotalAmount'], 2, ',', '.'); ?>
                      · <?php echo htmlspecialchars($pedido['Status']); ?>
                    </span>
                  </li>
                <?php endwhile; ?>
              <?php endif; ?>
            </ul>
          </div>
          <div class="chart-card">
            <h3>Controle de Estoque</h3>
            <ul class="dashboard-list">
              <?php if ($estoqueBaixo->num_rows === 0): ?>
                <li class="dashboard-list-empty">Nenhum produto com estoque baixo.</li>
              <?php else: ?>
                <?php while ($produto = $estoqueBaixo->fetch_assoc()): ?>
                  <li class="dashboard-list-item">
                    <span class="item-title"><?php echo htmlspecialchars($produto['Name']); ?></span>
                    <span class="item-subtext">
                      <?php echo (int) $produto['Stock']; ?> em estoque
                      (mínimo: <?php echo (int) $produto['MinStock']; ?>)
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