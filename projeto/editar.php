<?php
// Define que o cookie de sessão será destruído ao fechar o navegador
ini_set('session.cookie_lifetime', 0);

// Inicia uma nova sessão ou resume a sessão existente
session_start();

// Tempo máximo permitido para a sessão em segundos (5 minutos)
$tempo_maximo = 300;

// Verifica se existe o registro do último acesso na sessão
if (isset($_SESSION['ultimo_acesso'])) {
    // Calcula o tempo passado desde o último acesso
    $tempo_passado = time() - $_SESSION['ultimo_acesso'];
    if ($tempo_passado > $tempo_maximo) {
        // Se o tempo passado for maior que o máximo, encerra a sessão
        session_unset();    // Remove todas as variáveis da sessão
        session_destroy();  // Destroi a sessão
        header('Location: index.php'); // Redireciona para a página de login
        exit(); // Encerra a execução do script
    }
}

// Atualiza o tempo do último acesso para o tempo atual
$_SESSION['ultimo_acesso'] = time();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: index.php');
    exit();
}
?>



<?php
// Cria uma conexão com o banco de dados MySQL
$conn = new mysqli('localhost', 'root', '', 'controle_estoque');

// Verifica se houve erro na conexão com o banco
if ($conn->connect_error) {
    // Se houver erro, termina o script mostrando a mensagem de erro
    die('Erro de conexão: ' . $conn->connect_error);
}

// Pega o ID do alimento a ser editado da URL (via método GET)
$id = $_GET['id'];

// Verifica se o formulário foi enviado via POST (atualização do alimento)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados do formulário para atualizar o alimento
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $tipo = $_POST['tipo'];
    $data_fabricacao = $_POST['data_fabricacao'];
    $data_validade = $_POST['data_validade'];

    // Monta a consulta SQL para atualizar os dados do alimento no banco
    $sql = "UPDATE alimentos SET nome='$nome', quantidade='$quantidade', tipo='$tipo', data_fabricacao='$data_fabricacao', data_validade='$data_validade' WHERE id=$id";
    
    // Executa a consulta de atualização
    $conn->query($sql);

    // Após a atualização, redireciona para a página de listagem
    header('Location: listar.php');
    exit(); // Encerra a execução do script
}

// Consulta para pegar os dados do alimento que será editado
$sql = "SELECT * FROM alimentos WHERE id=$id";
$result = $conn->query($sql);

// Recupera os dados do alimento como um array associativo
$alimento = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en"> <!-- Define o idioma da página como inglês -->
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade -->
    <link rel="stylesheet" href="editar.css"> <!-- Link para o arquivo CSS externo -->
    <title>Editar</title> <!-- Título da aba no navegador -->
</head>
<body>
    <main> <!-- Conteúdo principal -->
        <div class="container"> <!-- Container para organizar o conteúdo -->
            <div>
                <h1>Editar Alimento</h1> <!-- Título da página -->
                <!-- Formulário para editar um alimento -->
                <form method="POST" action="">
                    <label for="nome">Nome:</label> <input type="text" name="nome" value="<?php echo htmlspecialchars($alimento['nome']); ?>" required><br>
                    <label for="quantidade">Quantidade:</label> <input type="text" name="quantidade" value="<?php echo $alimento['quantidade']; ?>" required><br>
                    <label for="tipo">Tipo:</label> 
                    <select name="tipo" required> <!-- Lista suspensa para selecionar o tipo de alimento -->
                        <option value="Perecível" <?php if ($alimento['tipo'] == 'Perecível') echo 'selected'; ?>>Perecível</option>
                        <option value="Não Perecível" <?php if ($alimento['tipo'] == 'Não Perecível') echo 'selected'; ?>>Não Perecível</option>
                        <option value="Congelado" <?php if ($alimento['tipo'] == 'Congelado') echo 'selected'; ?>>Congelado</option>
                        <option value="Outros" <?php if ($alimento['tipo'] == 'Outros') echo 'selected'; ?>>Outros</option>
                    </select><br>
                    <label for="data_fabricacao">Data de Fabricação:</label> <input type="date" name="data_fabricacao" value="<?php echo $alimento['data_fabricacao']; ?>"><br>
                    <label for="data_validade">Data de Validade:</label> <input type="date" name="data_validade" value="<?php echo $alimento['data_validade']; ?>"><br>
                    <input type="submit" value="Salvar Alterações" id="salvar"> <!-- Botão para enviar o formulário -->
                </form>
                <!-- Link para cancelar a edição e voltar para a lista -->
                <a href="listar.php">Cancelar</a>
            </div>
        </div>
    </main>
</body>
</html>



