<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "siscreche"); // Ajuste conforme seu banco

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$nome = $_POST['nome'];
$senha = $_POST['senha'];

// Consulta ao banco
$sql = "SELECT * FROM usuarios WHERE nome = ? AND senha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nome, $senha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    // Salvar dados na sessão
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo'] = $usuario['tipo'];

    // Redirecionamento com base no tipo
    if ($usuario['tipo'] === 'admin') {
        header("Location: diretorias.php");
    } else {
        header("Location: painel.php"); // Página comum para usuários normais
    }
    exit;
} else {
    echo "<script>alert('Login ou senha inválidos'); window.location.href = 'index.php';</script>";
}
?>
