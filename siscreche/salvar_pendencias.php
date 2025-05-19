<?php
session_start();
include_once("config.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'] ?? 0;
    $id_iniciativa = intval($_POST['id_iniciativa'] ?? 0);
    $problema = mysqli_real_escape_string($conexao, $_POST['problema'] ?? '');
    $contramedida = mysqli_real_escape_string($conexao, $_POST['contramedida'] ?? '');
    $prazo = mysqli_real_escape_string($conexao, $_POST['prazo'] ?? '');
    $responsavel = mysqli_real_escape_string($conexao, $_POST['responsavel'] ?? '');

    if ($problema || $contramedida || $prazo || $responsavel) {
        $query = "INSERT INTO pendencias (id_usuario, id_iniciativa, problema, contramedida, prazo, responsavel) 
                  VALUES ('$id_usuario', '$id_iniciativa', '$problema', '$contramedida', '$prazo', '$responsavel')";

        if (mysqli_query($conexao, $query)) {
            echo json_encode(['status' => 'ok', 'id' => mysqli_insert_id($conexao)]);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => mysqli_error($conexao)]);
        }
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Campos vazios']);
    }
}
