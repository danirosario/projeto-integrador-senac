<?php
require_once("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT idUser, passwordHash FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $hashed_password_from_db = $row['passwordHash'];

        if (password_verify($password, $hashed_password_from_db)) {
            $_SESSION['user_id'] = $row['idUser'];
            header("Location: client/shop.php");
            exit();
        } else {
            $erro = "E-mail ou senha inválidos.";
        }
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Login - CriArty</title>
</head>

<body>
    <section class="login-page">

        <!-- Lado esquerdo -->
        <div class="left-side">
            <div class="brand">
                <img src="images/logo.png" alt="Logo CriArty" class="logo">

                <h1>CriArty</h1>

                <p>
                    Bem vindo de volta!<br> Por favor, faça login para continuar.
                </p>
            </div>
        </div>

        <!-- Lado direito -->
        <div class="right-side">

            <div class="login-container">

                <h2>Login</h2>

                <form action="#" method="post">

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" placeholder="Digite seu e-mail" id="email" name="email" required>
                    </div>

                    <div class="input-group">
                        <label>Senha</label>
                        <input type="password" placeholder="Digite sua senha" id="password" name="password" required>
                    </div>

                    <button class="btn-login" type="submit">
                        Entrar
                    </button>

                </form>

                <p class="footer-text">
                    Não tem uma conta?
                    <a href="register.php">Cadastre-se</a>
                </p>

            </div>

        </div>

    </section>
</body>

</html>