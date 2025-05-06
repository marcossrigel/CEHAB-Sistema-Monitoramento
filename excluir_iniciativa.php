<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include("config.php");

if (!isset($_GET['id'])) {
    echo "ID não fornecido.";
    exit;
}

$id = intval($_GET['id']);

$check = $conexao->query("SELECT * FROM iniciativas WHERE id = $id");
if ($check->num_rows == 0) {
    echo "Iniciativa não encontrada.";
    exit;
}

$delete = $conexao->query("DELETE FROM iniciativas WHERE id = $id");

if ($delete) {
    echo "<script>window.location.href='visualizar.php';</script>";
} else {
    echo "Erro ao excluir: " . $conexao->error;
}
?>
