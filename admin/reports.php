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
                <!-- <h2>CriArty</h2> -->
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
                        <span class="stat-number">100</span>
                        <p class="stat-label">Pedidos do mês</p>
                    </div>

                    <!-- Bloco 2: Faturamento -->
                    <div class="container-reports">
                        <span class="stat-number">R$ 49,85</span>
                        <p class="stat-label">Faturamento</p>
                    </div>
                </div>

                <div class="reports-grid">
                    <!-- Bloco 3: Estoque -->
                    <div class="container-reports">
                        <h3 class="stat-label-top">Produtos com mais estoque</h3>

                        <div class="product-info-row">
                            <p class="stat-product-name">Caneca Personalizada</p>
                            <span class="stat-subtext">100 em estoque</span>
                        </div>
                    </div>

                    <!-- Bloco 4: Histórico -->
                    <div class="container-reports">
                        <h3 class="stat-label">Últimos 6 meses</h3>

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