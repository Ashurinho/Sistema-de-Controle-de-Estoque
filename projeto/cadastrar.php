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

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Se o formulário foi enviado (POST)
    $nome = $_POST['nome']; // Recebe o nome do alimento
    $quantidade = $_POST['quantidade']; // Recebe a quantidade
    $unidade = $_POST['unidade']; // Recebe a unidade de medida
    $data_validade = $_POST['data_validade']; // Recebe a data de validade
    $data_entrada = $_POST['data_entrada']; // Recebe a data de entrada
    $fornecedor = $_POST['fornecedor']; // Recebe o fornecedor
    $lote = $_POST['lote']; // Recebe o lote
    $observacoes = $_POST['observacoes']; // Recebe as observações

    // Monta a query de inserção dos dados
    $sql = "INSERT INTO alimentos 
        (nome, quantidade, unidade, data_validade, data_entrada, fornecedor, lote, observacoes)
        VALUES 
        ('$nome', '$quantidade', '$unidade', '$data_validade', '$data_entrada', '$fornecedor', '$lote', '$observacoes')";
    $conn->query($sql); // Executa a query

    header('Location: listar.php'); // Redireciona para a lista após cadastrar
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define o charset da página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade -->
    <link rel="stylesheet" href="cadastrar.css"> <!-- Importa o CSS -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> <!-- Ícone da aba do navegador -->
    <title>Cadastro de Alimento</title> <!-- Título da aba -->
</head>
<body>
    <main>
        <div class="container"> <!-- Container centralizado -->
            <div>
                <h1>Cadastrar Alimento</h1> <!-- Título do formulário -->
                <form method="POST" action=""> <!-- Formulário de cadastro -->
                    <label for="nome">Nome do Alimento:</label>
                    <input type="text" name="nome" required>
                    <!-- Campo para nome -->

                    <label for="quantidade">Quantidade:</label>
                    <input type="number" name="quantidade" min="0" step="any" required>
                    <!-- Campo para quantidade -->

                    <label for="unidade">Unidade de Medida:</label>
                    <select name="unidade" required>
                        <option value="kg">Kg</option>
                        <option value="l">Litro</option>
                        <option value="un">Unidade</option>
                        <option value="pct">Pacote</option>
                        <option value="cx">Caixa</option>
                    </select>
                    <!-- Campo para unidade de medida -->

                    <label for="data_validade">Data de Validade:</label>
                    <input type="date" name="data_validade">
                    <!-- Campo para data de validade -->

                    <label for="data_entrada">Data de Entrada:</label>
                    <input type="date" name="data_entrada" value="<?php echo date('Y-m-d'); ?>">
                    <!-- Campo para data de entrada, preenchido com a data atual -->

                    <label for="fornecedor">Fornecedor:</label>
                    <input type="text" name="fornecedor">
                    <!-- Campo para fornecedor -->

                    <label for="lote">Lote:</label>
                    <input type="text" name="lote">
                    <!-- Campo para lote -->

                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" rows="3" style="resize:vertical;"></textarea>
                    <!-- Campo para observações -->

                    <input type="submit" value="Cadastrar" id="cadastrar">
                    <!-- Botão para cadastrar -->
                </form>
                <a href="listar.php">Voltar para Lista</a> <!-- Link para voltar para a lista -->
            </div>
        </div>