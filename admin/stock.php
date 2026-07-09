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
                <!-- <h2>CriArty</h2> -->
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
                            <tr class="stock-item">
                                <td>Produto 1</td>
                                <td>40</td>
                                <td>OK</td>
                                <td>
                                    <div class="stock-actions">
                                        <button class="btn-restock">Repor</button>
                                        <button class="btn-withdraw">Retirar</button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="stock-item">
                                <td>Produto 2</td>
                                <td>15</td>
                                <td>BAIXO</td>
                                <td>
                                    <div class="stock-actions">
                                        <button class="btn-restock">Repor</button>
                                        <button class="btn-withdraw">Retirar</button>
                                    </div>
                                </td>
                            </tr>
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