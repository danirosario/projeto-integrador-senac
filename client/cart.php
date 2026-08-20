<?php 
require_once('../config.php'); 

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 

$cart_products = []; 
$total_price   = 0.0; 

if (!empty($_SESSION['cart'])) { 

    $ids = array_keys($_SESSION['cart']); // Obtém apenas as chaves (que são os IDs dos produtos) do array armazenado na sessão
    
    // Cria uma string de interrogações separadas por vírgula baseada no número de IDs (ex: '?, ?, ?'); 
    // Isso é necessário para fazer o Prepared Statement dinâmico no operador IN do SQL

    $placeholders = implode(',', array_fill(0, count($ids), '?')); 

    $stmt = $conn->prepare("SELECT idProduct, Name, BasePrice FROM product WHERE idProduct IN ($placeholders) AND isActive = 1"); 
    
    // Cria a string de tipos para o bind (ex: se forem 3 IDs, gera 'iii' indicando 3 inteiros)
    $types = str_repeat('i', count($ids)); 
    
    // Vincula os IDs reais às interrogações (?) do SQL usando o operador '...' (argument unpacking)
    $stmt->bind_param($types, ...$ids); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

    if ($result && $result->num_rows > 0) { 
        while ($product = $result->fetch_assoc()) { 
            $id = $product['idProduct'];
            
            $quantity = $_SESSION['cart'][$id]; // Busca a quantidade desse produto específico armazenada originalmente na sessão
            $subtotal = $product['BasePrice'] * $quantity; 
            $total_price += $subtotal; 

            $cart_products[] = [ 
                'id' => $id, 
                'name' => $product['Name'], 
                'price' => $product['BasePrice'], 
                'quantity' => $quantity, 
                'subtotal' => $subtotal 
            ]; 
        } 
    } 
}

?> 
<!DOCTYPE html> 
<html lang="pt-BR"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="../css/client-styles/shop.css"> 
    <link rel="stylesheet" href="../css/client-styles/footer.css"> 
    <link rel="stylesheet" href="../css/client-styles/cart.css"> 
    <title>Meu Carrinho</title> 
</head> 
<body> 
    <nav class="navbar"> 
        <div class="nav-logo"> 
            <a href="shop.php">Logo</a> 
        </div> 
        <ul class="nav-links"> 
            <li><a href="shop.php">Home</a></li> 
            <li><a href="productsList.php">Produtos</a></li> 
            <?php if (!empty($_SESSION['user_id'])): ?> 
                <li><a href="cart.php">Meu Carrinho</a></li> 
            <?php endif; ?> 
            <li><a href="#contato">Contato</a></li> 
        </ul> 
        <div class="perfil"> 
            <?php if (!empty($_SESSION['user_id'])): ?> 
                <a href="../logout.php">Logout</a> 
            <?php else: ?> 
                <a href="../login.php">Login</a> 
            <?php endif; ?> 
        </div> 
    </nav> 

    <div class="cart-container"> 
        <div class="cart-title"> 
            <h2>Meu Carrinho</h2> 
        </div> 
        <div class="cart-grid"> 
            <?php if (!empty($cart_products)): ?> 
                <ul class="list-itens"> 
                    <?php foreach ($cart_products as $item): ?> 
                        <li class="item-cart"> 
                            <span class="product-name"> 
                                <?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>) 
                            </span> 
                            <span class="product-price"> 
                                R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?> 
                            </span> 
                            <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" class="remove" title="Remover item">x</a> 
                        </li> 
                    <?php endforeach; ?> 
                </ul> 
                <div class="side"> 
                    <div class="total-cart"> 
                        <strong>Total: R$ <?php echo number_format($total_price, 2, ',', '.'); ?></strong> 
                    </div> 
                    <button class="finalize-order">Finalizar Compra</button> 
                    <div class="clear-cart-box"> 
                        <a href="clear_cart.php" onclick="return confirm('Tem certeza que deseja esvaziar o carrinho?');" class="clear-cart-link"> 
                            Esvaziar Carrinho 
                        </a> 
                    </div> 
                </div> 
            <?php else: ?> 
                <div class="empty-cart-box"> 
                    <p>Seu carrinho está vazio.</p> 
                    <a href="shop.php" class="back-to-shop">Voltar para a loja</a> 
                </div> 
            <?php endif; ?> 
        </div> 
    </div> 

    <footer id="contato"> 
        <div class="footer-container"> 
            <div class="footer-column contato-block"> 
                <h2>Contato</h2> 
                <div class="contato-content"> 
                    <p>Entre em contato conosco:</p> 
                    <ul> 
                        <li>Email: <a href="mailto:contato@criarty.com">contato@criarty.com</a></li> 
                        <li>Telefone: <a href="tel:+5511999999999">(11) 99999-9999</a></li> 
                    </ul> 
                </div> 
            </div> 
            <div class="footer-column social-block"> 
                <h2>Siga-nos</h2> 
                <ul> 
                    <li><a href="https://instagram.com" target="_blank">Instagram</a></li> 
                </ul> 
            </div> 
            <div class="footer-column credits-block"> 
                <p>&copy; 2026 CriArty.<br>Todos os direitos reservados.</p> 
                <a href="#" class="back-to-top">Retornar ao topo</a> 
            </div> 
        </div> 
    </footer> 
</body> 
</html>
