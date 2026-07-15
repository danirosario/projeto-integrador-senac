<?php
require_once("../conexao.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "ID Inválido!";
    exit;
}

$categories = $conn->query("SELECT idCategory, Name FROM category WHERE isActive = 1 ORDER BY Name");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name         = $_POST['product-name'];
    $price        = floatval($_POST['price']);
    $stock        = intval($_POST['stock']);
    $description  = $_POST['description'];
    $img          = $_POST['image-url'];
    $category     = intval($_POST['category']);
    $idPost       = intval($_POST['id']);

    if ($idPost > 0) {
        $stmt = $conn->prepare("UPDATE product SET Name = ?, BasePrice = ?, Stock = ?, Description = ?, ImageURL = ?, Category_idCategory = ? WHERE idProduct = ?");
        $stmt->bind_param("sdisiii", $name, $price, $stock, $description, $img, $category, $idPost);

        if ($stmt->execute()) {
            echo "<script> alert('Editado com sucesso!'); window.location.href = 'products.php'; </script>";
            exit;
        } else {
            echo "Erro ao editar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "ID inválido para atualização.";
    }
}

// Buscar dados do produto para preencher o formulário
$stmt = $conn->prepare("SELECT Name, BasePrice, Stock, Description, ImageURL, Category_idCategory FROM product WHERE idProduct = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Produto não encontrado!";
    exit;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin-styles/products.css" />
    <title>Editar Produto</title>
</head>

<body>
    <div class="modal" id="myModal" style="display:block; position:relative; background:none; box-shadow:none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="window.location.href='products.php'">&times;</button>
                    <h4 class="modal-title">Editar Produto</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <div class="form-group">
                            <label for="product-name">Nome do Produto</label>
                            <input type="text" class="form-control" id="product-name" name="product-name"
                                value="<?php echo htmlspecialchars($product['Name']); ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="category">Categoria</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Selecione...</option>
                                <?php
                                if (isset($categories)):
                                    while ($cat = $categories->fetch_assoc()):
                                        $selected = ($cat['idCategory'] == $product['Category_idCategory']) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo (int) $cat['idCategory'] ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($cat['Name']) ?>
                                        </option>
                                    <?php
                                    endwhile;
                                endif;
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                required><?php echo htmlspecialchars($product['Description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Preço</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                value="<?php echo $product['BasePrice']; ?>" required />
                        </div>

                        <div class="form-group">
                            <label for="image-url">URL da Imagem</label>
                            <input type="text" class="form-control" id="image-url" name="image-url"
                                value="<?php echo htmlspecialchars($product['ImageURL']); ?>" />
                        </div>

                        <div class="form-group">
                            <label for="stock">Quantidade em Estoque</label>
                            <input type="number" class="form-control" id="stock" name="stock"
                                value="<?php echo $product['Stock']; ?>" required />
                        </div>

                        <div class="modal-footer" style="padding: 15px 0 0 0; border-top: none;">
                            <button type="submit" class="btn btn-success">Salvar Alterações</button>
                            <a href="products.php" class="btn btn-default" style="text-decoration: none; margin-left: 10px;">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src = "../js/modal.js"></script>
</body>

</html>