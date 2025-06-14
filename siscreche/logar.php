<?php
session_start();
include_once('config.php');

$login = $_POST['nome'];
$senha = $_POST['senha'];

// Usando prepared statement para evitar SQL injection
$query = "SELECT * FROM usuarios WHERE nome = ? AND senha = ?";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, "ss", $login, $senha);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $usuario = mysqli_fetch_assoc($result);

    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo'] = $usuario['tipo'];

    if ($usuario['tipo'] === 'admin') {
        header('Location: painel_admin.php');
    } else {
        header('Location: home.php');
    }
    exit;
} else {
    echo "<script>alert('Erro: Usuário ou Senha inválidos'); window.location.href = 'login.php';</script>";
}
?>
