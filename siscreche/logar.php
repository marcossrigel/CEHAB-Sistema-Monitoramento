<<<<<<< HEAD
<?php
session_start();
include_once('config.php');

$login = $_POST['nome'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($login) || empty($senha)) {
    echo "<script>alert('Preencha todos os campos.'); window.location.href = 'login.php';</script>";
    exit;
}

$query = "SELECT * FROM usuarios WHERE nome = ? AND senha = ?";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, "ss", $login, $senha);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) === 1) {
    $usuario = mysqli_fetch_assoc($result);

    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo_usuario'] = $usuario['tipo'];

    if ($usuario['tipo'] === 'admin') {
        header('Location: diretorias.php');
    } else {
        header('Location: index.php');
    }
    exit;
} else {
    echo "<script>alert('Erro: Usuário ou Senha inválidos'); window.location.href = 'login.php';</script>";
}
?>
=======
<?php
session_start();
include_once('config.php');

$login = $_POST['nome'];
$senha = $_POST['senha'];

$query = "SELECT * FROM usuarios WHERE nome = ? AND senha = ?";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, "ss", $login, $senha);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $usuario = mysqli_fetch_assoc($result);

    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo_usuario'] = $usuario['tipo']; // ✅ nome agora compatível

    if ($usuario['tipo'] === 'admin') {
        header('Location: diretorias.php');
    } else {
        header('Location: home.php');
    }
    exit;
} else {
    echo "<script>alert('Erro: Usuário ou Senha inválidos'); window.location.href = 'login.php';</script>";
}
?>
>>>>>>> 4d19e14123300427efc4d292b2732c3b3c8eba4f
