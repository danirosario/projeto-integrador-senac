<?php
require_once("../config.php");
require_once("auth_check.php");

// Total de pedidos
$result = $conn->query("SELECT COUNT(*) AS total FROM `order`");
$totalData = $result->fetch_assoc(); // array associativo com o total de pedidos
$totalOrders = $totalData['total'];  // total de pedidos cadastrados

// faturamento total 
$resultSum = $conn->query("SELECT SUM(TotalAmount) AS totalValor FROM `order`");
$sum = $resultSum->fetch_assoc();

// Armazena o valor total. O operador '?? 0' garante que, se o banco estiver vazio, o valor será 0.
$totalRevenue = $sum['totalValor'] ?? 0;

// BUSCAR PEDIDOS CADASTRADOS (para a tabela de listagem)
// Faz um INNER JOIN entre a tabela `order` (o) e `customer` (c) para trazer o nome do cliente.
// O resultado é ordenado pela data do pedido mais recente para o mais antigo (DESC).
$orders = $conn->query("SELECT o.idOrder, o.OrderDate, o.Status, o.PaymentStatus, o.TotalAmount, c.Name AS CustomerName
    FROM `order` o
    INNER JOIN customer c ON c.idCustomer = o.Customer_idCustomer
    ORDER BY o.OrderDate DESC");
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin-styles/orders.css" />
    <title>CriArty - Pedidos</title>
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
                    <li><a href="orders.php" class="active">Pedidos</a></li>
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
                    Gerenciamento de Pedidos
                </p>

                <!-- PEDIDOS -->

                <section class="orders-section">
                    <!-- <h3>Pedidos</h3> -->
                    <div class="orders-info">
                        <span>Pedido</span>
                        <span>Cliente</span>
                        <span>Total</span>
                        <span>Status</span>
                        <span>Pagamento</span>
                        <span>Data</span>
                    </div>

                    <ul>
                        <?php
                        // Verifica se a consulta retornou alguma linha do banco de dados
                        if ($orders && $orders->num_rows > 0):

                            while ($order = $orders->fetch_assoc()):
                                ?>
                                <li class="order-item">
                                    <span># <?php echo $order["idOrder"]; ?> </span>
                                    <span> <?php echo htmlspecialchars($order["CustomerName"]); ?> </span>
                                    <span>R$ <?php echo number_format($order["TotalAmount"], 2, ',', '.'); ?> </span>
                                    <span> <?php echo htmlspecialchars($order["Status"]); ?> </span>
                                    <span> <?php echo htmlspecialchars($order["PaymentStatus"]); ?> </span>
                                    <span> <?php echo date('d/m/Y H:i', strtotime($order["OrderDate"])); ?> </span>
                                </li>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <li class="order-item">
                                <span>Nenhum pedido encontrado.</span>
                            </li>
                        <?php
                        endif;
                        ?>
                    </ul>
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