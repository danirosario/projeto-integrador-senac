<?php
require_once 'config.php';

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cpf = $_POST['cpf'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($confirmPassword != $password) {
        echo "<script>alert('As senhas não coincidem.'); window.history.back();</script>";
    } else {
        $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,}$/";

        if (!preg_match($passwordPattern, $password)) {
            echo "<script>alert('A senha deve conter pelo menos 8 caracteres, uma letra maiúscula, um número e um caractere especial.'); window.history.back();</script>";
        } else {
            // 1. Buscamos o idRole na tabela 'role' onde o name é 'user'
            $roleName = UserRole::USER->value;
            $stmtRole = $conn->prepare("SELECT idRole FROM role WHERE name = ?");
            //A variável $roleName passa a valer a string 'user'.
            $stmtRole->bind_param("s", $roleName);
            $stmtRole->execute();
            $resultRole = $stmtRole->get_result();

            if ($rowRole = $resultRole->fetch_assoc()) {
                $idRole = $rowRole['idRole'];
                $stmtRole->close();

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                if(empty($cpf)) {
                    $cpf = null; 
                } else {
                    $cpf = preg_replace('/\D/', '', $cpf);
                }

                $stmt = $conn->prepare("INSERT INTO user (name, email, passwordHash, phone, cpf, Role_idRole) VALUES (?, ?, ?, ?, ?, ?)");

                $stmt->bind_param("sssssi", $name, $email, $hashedPassword, $phone, $cpf, $idRole);

                if ($stmt->execute()) {
                    echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='login.php';</script>";
                } else {
                    echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "'); window.history.back();</script>";
                }
                $stmt->close();
            } else {
                echo "<script>alert('Erro de configuração do sistema: Função padrão não encontrada no banco.'); window.history.back();</script>";
                $stmtRole->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Cadastro - CriArty</title>
</head>

<body>
    <section class="login-page">

        <!-- Lado direito -->
        <div class="right-side">
            <div class="login-container">

                <h2>Cadastro</h2>

                <form action="#" method="post">

                    <div class="input-group">
                        <label>Nome<span style="color: red;"> *</span></label>
                        <input type="text" placeholder="Digite seu nome" name="name" required>
                    </div>

                    <div class="input-group">
                        <label>Email<span style="color: red;"> *</span></label>
                        <input type="email" placeholder="Digite seu e-mail" name="email" required>
                    </div>

                    <div class="row">
                        <div class="input-group">
                            <label>Telefone</label>
                            <input type="tel" placeholder="Digite seu telefone" name="phone">
                        </div>

                        <div class="input-group">
                            <label>CPF</label>
                            <input type="text" placeholder="Digite seu CPF" name="cpf">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Senha<span style="color: red;"> *</span></label>
                        <input type="password" placeholder="Digite sua senha" name="password" required>
                    </div>

                    <div class="input-group">
                        <label>Confirmar Senha<span style="color: red;"> *</span></label>
                        <input type="password" placeholder="Digite sua senha novamente" name="confirm_password"
                            required>
                    </div>

                    <button class="btn-login">
                        Cadastrar
                    </button>

                </form>

                <p class="footer-text">
                    Já tem uma conta?
                    <a href="login.php">Login</a>
                </p>

            </div>
        </div>

        <!-- Lado esquerdo -->
        <div class="left-side">
            <div class="brand">
                <img src="images/logo.png" alt="Logo CriArty" class="logo">

                <h1>CriArty</h1>

                <p>
                    Seja bem vindo a CriArty! <br>Cadastre-se e aproveite a experiência de personalizar seus produtos
                    com exclusividade.
                </p>
            </div>
        </div>

    </section>
</body>

</html>