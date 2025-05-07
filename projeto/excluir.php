<?php
// Define que o cookie de sessão será destruído ao fechar o navegador
ini_set('session.cookie_lifetime', 0);

// Inicia uma nova sessão ou retoma a sessão existente
session_start();

// Tempo máximo permitido para a sessão em segundos (5 minutos)
$tempo_maximo = 300;

// Verifica se existe o registro do último acesso na sessão
if (isset($_SESSION['ultimo_acesso'])) {
    // Calcula o tempo que passou desde o último acesso
    $tempo_passado = time() - $_SESSION['ultimo_acesso'];
    if ($tempo_passado > $tempo_maximo) {
        // Se o tempo passado for maior que o máximo, encerra a sessão
        session_unset();    // Remove todas as variáveis da sessão
        session_destroy();  // Destroi a sessão
        header('Location: index.php'); // Redireciona para a página de login
        exit(); // Encerra a execução do script
    }
}

// Atualiza o tempo do último acesso com o tempo atual
$_SESSION['ultimo_acesso'] = time();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: index.php');
    exit(); // Encerra o script, evitando o acesso a esta página
}
?>



<?php
// Cria a conexão com o banco de dados MySQL
$conn = new mysqli('localhost', 'root', '', 'controle_estoque');

// Verifica se ocorreu algum erro na conexão
if ($conn->connect_error) {
    // Se houver erro, o script é interrompido e a mensagem de erro é exibida
    die('Erro de conexão: ' . $conn->connect_error);
}

// Pega o ID do alimento que será excluído da URL (via método GET)
$id = $_GET['id'];

// Monta a consulta SQL para excluir o alimento com o ID especificado
$sql = "DELETE FROM alimentos WHERE id=$id";

// Executa a consulta SQL de exclusão
$conn->query($sql);

// Após a exclusão, redireciona o usuário para a página de listagem de alimentos
header('Location: listar.php');
exit(); // Encerra a execução do script
?>

