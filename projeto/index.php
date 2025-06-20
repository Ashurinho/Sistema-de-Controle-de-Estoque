<?php
// Define que o cookie de sessão será destruído ao fechar o navegador
ini_set('session.cookie_lifetime', 0);

// Inicia uma nova sessão ou resume a sessão existente
session_start();

// Definindo usuário e senha fixos (hardcoded)
$usuario_correto = 'fontes';
$senha_correta = 'cozinha676997';

// Verifica se o usuário já está logado na sessão
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    // Redireciona para a página listar.php se já estiver logado
    header('Location: listar.php');
    exit();
}

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega o valor enviado no campo "usuario" do formulário
    $usuario = $_POST['usuario'];
    // Pega o valor enviado no campo "senha" do formulário
    $senha = $_POST['senha'];

    // Compara o usuário e senha enviados com os valores corretos
    if ($usuario === $usuario_correto && $senha === $senha_correta) {
        // Define uma variável de sessão indicando que o usuário está logado
        $_SESSION['logado'] = true;
        // Redireciona para a página listar.php
        header('Location: listar.php');
        exit();
    } else {
        // Define a variável de erro caso login ou senha estejam incorretos
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="en"> <!-- Define o idioma da página como inglês -->
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres da página como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade para dispositivos móveis -->
    <link rel="stylesheet" href="style.css"> <!-- Linka o arquivo CSS externo -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> <!-- Ícone da aba do navegador -->
    <title>Login de Usuário</title> <!-- Título da aba do navegador -->
</head>
<body>
    <main> <!-- Conteúdo principal da página -->
        <div class="container"> <!-- Container principal para organizar o conteúdo -->
            <div>
                <!-- Imagem de logo -->
                <img src="img/logoFontes.jpg" alt="Logo do Fontes" id="logoFontes">
                
                <!-- Exibe a mensagem de erro em vermelho, se houver -->
                <?php if (isset($erro)) { echo "<p style='color:red;'>$erro</p>"; } ?>

                <!-- Formulário de login -->
                <form method="POST" action="">
                    <!-- Campo de usuário -->
                    <div class="input">
                        <label for="usuario">Usuário:</label>
                        <input type="text" name="usuario" class="inputs" required>
                    </div><br>

                    <!-- Campo de senha -->
                    <div class="input">
                        <label for="senha">Senha:</label>
                        <input type="password" name="senha" class="inputs" required>
                    </div><br>
                    <!-- Botão de enviar -->
                    <div class="btn-container">
                        <input type="submit" value="Entrar" id="entrar">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>