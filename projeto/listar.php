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

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    // Se houver erro, termina o script mostrando a mensagem
    die('Erro de conexão: ' . $conn->connect_error);
}

// Pega o valor da pesquisa enviado via GET, se existir; caso contrário, define como string vazia
$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

// Monta a consulta SQL usando o valor da pesquisa
$sql = "SELECT * FROM alimentos WHERE nome LIKE '%$pesquisa%'";

// Executa a consulta no banco
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en"> <!-- Define o idioma da página como inglês -->
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade -->
    <link rel="stylesheet" href="listar.css"> <!-- Link para o arquivo CSS externo -->
    <title>Controle de Estoque</title> <!-- Título da aba no navegador -->
</head>
<body>
    <main> <!-- Conteúdo principal -->
        <div>
        <div class="titulo">
            <h2>Armazém Fontes</h2> <!-- Título da página -->
        </div>

        <div class="pesquisa">
            <!-- Formulário para buscar alimentos -->
            <form method="GET" action="">
                <input type="text" name="pesquisa" placeholder="Pesquisar alimento..." value="<?php echo htmlspecialchars($pesquisa); ?>">
                <input type="submit" value="🔎"> <!-- Botão de busca -->
            </form>
        </div>

        <div class="cadastrar-btn">
            <!-- Link para a página de cadastro de novo alimento -->
            <a href="cadastrar.php">Cadastrar Novo Alimento</a><br><br>
        </div>

        <div class="lista">
            <!-- Tabela para listar os alimentos -->
            <table>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Tipo</th>
                    <th>Data de Fabricação</th>
                    <th>Data de Validade</th>
                    <th>Ações</th> <!-- Coluna para editar ou excluir -->
                </tr>

                <!-- Loop que percorre os resultados da consulta -->
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- Exibe os dados de cada alimento -->
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo $row['quantidade']; ?></td>
                        <td><?php echo htmlspecialchars($row['tipo']); ?></td>
                        <td><?php echo $row['data_fabricacao']; ?></td>
                        <td><?php echo $row['data_validade']; ?></td>
                        <td>
                            <!-- Links para editar ou excluir o alimento -->
                            <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a>
                            <a href="excluir.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?> <!-- Fim do loop -->
            </table>
        </div>
        </div>
    </main>
</body>
</html>

