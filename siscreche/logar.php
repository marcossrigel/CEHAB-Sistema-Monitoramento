<?php
session_start();
include_once('config.php');

$login = $_POST['nome'];
$senha = $_POST['senha'];

$query = "SELECT * FROM usuarios WHERE nome = '$login' AND senha = '$senha'";

$result = mysqli_query($conexao, $query);

if (mysqli_num_rows($result) == 1) {
    $usuario = mysqli_fetch_assoc($result);
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];

    header('Location: home.php');
    exit;
} else {
    echo "<script>alert('Erro: Usuário ou Senha inválidos'); window.location.href = 'login.php';</script>";
}
?>
