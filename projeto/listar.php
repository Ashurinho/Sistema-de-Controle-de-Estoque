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

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : ''; // Pega o valor da pesquisa, se existir, senão vazio
$sql = "SELECT * FROM alimentos WHERE nome LIKE '%$pesquisa%'"; // Monta a consulta SQL para buscar alimentos pelo nome
$result = $conn->query($sql); // Executa a consulta no banco
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define o charset da página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade -->
    <link rel="stylesheet" href="listar.css"> <!-- Importa o CSS -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> <!-- Ícone da aba do navegador -->
    <title>Controle de Estoque</title> <!-- Título da aba -->
</head>
<body>
    <main>
        <div>
            <div class="titulo">
                <h2>Cozinha Escolar - Controle de Estoque</h2> <!-- Título principal da página -->
            </div>

            <div class="pesquisa">
                <form method="GET" action="">
                    <input type="text" name="pesquisa" placeholder="Pesquisar alimento..." value="<?php echo htmlspecialchars($pesquisa); ?>">
                    <!-- Campo de pesquisa preenchido com o valor atual da busca -->
                    <input type="submit" value="🔎"> <!-- Botão de pesquisa -->
                </form>
            </div>

            <div class="cadastrar-btn">
                <a href="cadastrar.php">Cadastrar Novo Alimento</a><br><br> <!-- Botão para cadastrar novo alimento -->
            </div>

            <div class="lista">
                <?php if ($result->num_rows > 0): ?> <!-- Se houver resultados na consulta -->
                    <table>
                        <tr>
                            <th>Nome</th>
                            <th>Quantidade</th>
                            <th>Unidade</th>
                            <th>Data de Validade</th>
                            <th>Data de Entrada</th>
                            <th>Fornecedor</th>
                            <th>Lote</th>
                            <th>Observações</th>
                            <th>Ações</th>
                        </tr>
                        <?php while($row = $result->fetch_assoc()): ?> <!-- Percorre cada linha do resultado -->
                            <tr>
                                <td><?php echo htmlspecialchars($row['nome']); ?></td> <!-- Nome do alimento -->
                                <td><?php echo $row['quantidade']; ?></td> <!-- Quantidade -->
                                <td><?php echo htmlspecialchars($row['unidade']); ?></td> <!-- Unidade -->
                                <td><?php echo $row['data_validade']; ?></td> <!-- Data de validade -->
                                <td><?php echo $row['data_entrada']; ?></td> <!-- Data de entrada -->
                                <td><?php echo htmlspecialchars($row['fornecedor']); ?></td> <!-- Fornecedor -->
                                <td><?php echo htmlspecialchars($row['lote']); ?></td> <!-- Lote -->
                                <td><?php echo htmlspecialchars($row['observacoes']); ?></td> <!-- Observações -->
                                <td>
                                    <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a> <!-- Link para editar -->
                                    <a href="excluir.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a> <!-- Link para excluir com confirmação -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?> <!-- Se não houver resultados -->
                    <p style="text-align:center; margin-top:20px;">Nenhum alimento cadastrado.</p>
                <?php endif;