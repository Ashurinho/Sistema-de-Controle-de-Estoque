<?php
ini_set('session.cookie_lifetime', 0); // Garante que o cookie de sessão expire ao fechar o navegador
session_start(); // Inicia a sessão

$tempo_maximo = 300; // Tempo máximo de sessão em segundos (5 minutos)
if (isset($_SESSION['ultimo_acesso'])) { // Verifica se existe registro do último acesso
    $tempo_passado = time() - $_SESSION['ultimo_acesso']; // Calcula o tempo passado desde o último acesso
    if ($tempo_passado > $tempo_maximo) { // Se passou do tempo máximo
        session_unset(); // Limpa variáveis de sessão
        session_destroy(); // Destroi a sessão
        header('Location: index.php'); // Redireciona para login
        exit();
    }
}
$_SESSION['ultimo_acesso'] = time(); // Atualiza o tempo do último acesso

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) { // Verifica se está logado
    header('Location: index.php'); // Se não, redireciona para login
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'controle_estoque'); // Conecta ao banco de dados
if ($conn->connect_error) { // Verifica erro de conexão
    die('Erro de conexão: ' . $conn->connect_error); // Exibe mensagem de erro e encerra
}

$id = $_GET['id']; // Pega o ID do alimento a ser editado via GET

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Se o formulário foi enviado (POST)
    $nome = $_POST['nome']; // Recebe o nome do alimento
    $quantidade = $_POST['quantidade']; // Recebe a quantidade
    $unidade = $_POST['unidade']; // Recebe a unidade de medida
    $data_validade = $_POST['data_validade']; // Recebe a data de validade
    $data_entrada = $_POST['data_entrada']; // Recebe a data de entrada
    $fornecedor = $_POST['fornecedor']; // Recebe o fornecedor
    $lote = $_POST['lote']; // Recebe o lote
    $observacoes = $_POST['observacoes']; // Recebe as observações

    // Monta a query de atualização dos dados
    $sql = "UPDATE alimentos SET 
        nome='$nome', 
        quantidade='$quantidade', 
        unidade='$unidade', 
        data_validade='$data_validade', 
        data_entrada='$data_entrada', 
        fornecedor='$fornecedor', 
        lote='$lote', 
        observacoes='$observacoes'
        WHERE id=$id";
    $conn->query($sql); // Executa a query

    header('Location: listar.php'); // Redireciona para a lista após salvar
    exit();
}

// Busca os dados atuais do alimento para preencher o formulário
$sql = "SELECT * FROM alimentos WHERE id=$id";
$result = $conn->query($sql);
$alimento = $result->fetch_assoc(); // Armazena os dados em um array associativo
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define o charset da página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade -->
    <link rel="stylesheet" href="editar.css"> <!-- Importa o CSS -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> <!-- Ícone da aba do navegador -->
    <title>Edição de Alimento</title> <!-- Título da aba -->
</head>
<body>
    <main>
        <div class="container"> <!-- Container centralizado -->
            <div>
                <h1>Editar Alimento</h1> <!-- Título do formulário -->
                <form method="POST" action=""> <!-- Formulário de edição -->
                    <label for="nome">Nome do Alimento:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($alimento['nome']); ?>" required>
                    <!-- Campo para nome, preenchido com valor atual -->

                    <label for="quantidade">Quantidade:</label>
                    <input type="number" name="quantidade" min="0" step="any" value="<?php echo $alimento['quantidade']; ?>" required>
                    <!-- Campo para quantidade -->

                    <label for="unidade">Unidade de Medida:</label>
                    <select name="unidade" required>
                        <!-- Opções de unidade, selecionando a atual -->
                        <option value="kg" <?php if ($alimento['unidade'] == 'kg') echo 'selected'; ?>>Kg</option>
                        <option value="l" <?php if ($alimento['unidade'] == 'l') echo 'selected'; ?>>Litro</option>
                        <option value="un" <?php if ($alimento['unidade'] == 'un') echo 'selected'; ?>>Unidade</option>
                        <option value="pct" <?php if ($alimento['unidade'] == 'pct') echo 'selected'; ?>>Pacote</option>
                        <option value="cx" <?php if ($alimento['unidade'] == 'cx') echo 'selected'; ?>>Caixa</option>
                    </select>

                    <label for="data_validade">Data de Validade:</label>
                    <input type="date" name="data_validade" value="<?php echo $alimento['data_validade']; ?>">
                    <!-- Campo para data de validade -->

                    <label for="data_entrada">Data de Entrada:</label>
                    <input type="date" name="data_entrada" value="<?php echo $alimento['data_entrada']; ?>">
                    <!-- Campo para data de entrada -->

                    <label for="fornecedor">Fornecedor:</label>
                    <input type="text" name="fornecedor" value="<?php echo htmlspecialchars($alimento['fornecedor']); ?>">
                    <!-- Campo para fornecedor -->

                    <label for="lote">Lote:</label>
                    <input type="text" name="lote" value="<?php echo htmlspecialchars($alimento['lote']); ?>">
                    <!-- Campo para lote -->

                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" rows="3" style="resize:vertical;"><?php echo htmlspecialchars($alimento['observacoes']); ?></textarea>
                    <!-- Campo para observações -->

                    <input type="submit" value="Salvar Alterações" id="salvar">
                    <!-- Botão para salvar -->
                </form>
                <a href="listar.php">Cancelar</a> <!-- Link para cancelar e voltar -->
            </div>
        </div>
    </main>
</body>