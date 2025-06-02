<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

include_once("config.php");

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;
if ($id_iniciativa === 0) {
    echo "<script>alert('ID da iniciativa inválido!'); window.location.href='visualizar.php';</script>";
    exit;
}

$query_nome = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
$result_nome = mysqli_query($conexao, $query_nome);
$nome_iniciativa = 'Desconhecida';
if ($row = mysqli_fetch_assoc($result_nome)) {
    $nome_iniciativa = $row['iniciativa'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_FILES['foto']['name'])) {
        $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);
        $nome_final = time() . '_' . basename($_FILES['foto']['name']);
        $caminho = "uploads/" . $nome_final;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho)) {
            $query = "INSERT INTO fotos (id_usuario, id_iniciativa, caminho, descricao) 
                      VALUES ('$id_usuario', '$id_iniciativa', '$nome_final', '$descricao')";

            if (mysqli_query($conexao, $query)) {
                echo "<script>alert('Foto salva com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao salvar no banco: " . mysqli_error($conexao) . "');</script>";
            }
        } else {
            echo "<script>alert('Erro ao mover a foto.');</script>";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload de Foto</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f1f1f1;
      margin: 0;
      padding: 10px;
    }
    .container {
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      font-size: 20px;
      text-align: center;
      color: #2c2c2c;
      margin-bottom: 20px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
      margin-bottom: 5px;
    }
    input[type="file"] {
      display: block;
      width: 100%;
      padding: 5px 0;
      font-size: 14px;
      margin-bottom: 10px;
    }
    textarea {
      width: 100%;
      height: 100px;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      resize: vertical;
      font-family: 'Poppins', sans-serif;
    }
    .button-group {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: space-between;
    }

    .button-group button {
    flex: 1 1 calc(33.333% - 10px);
    min-width: 100px;
    padding: 10px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    color: white;
    }
    .salvar { background-color: #28a745; }
    .galeria { background-color: #007bff; }
    .voltar { background-color: #6c757d; }
  </style>
</head>

<body>
  <div class="container">
    <h2><?php echo htmlspecialchars($nome_iniciativa); ?> - Acompanhamento Fotográfico</h2>
    <form method="post" enctype="multipart/form-data">
      <label for="foto">Foto</label>
      <input type="file" name="foto" id="foto" accept="image/*">

      <label for="descricao">Descrição da foto</label>
      <textarea name="descricao" id="descricao" placeholder="Descreva a foto..."></textarea>

      <div class="button-group">
        <button type="submit" class="salvar">Salvar</button>
        <button type="button" class="galeria" onclick="window.location.href='galeria.php?id_iniciativa=<?php echo $id_iniciativa; ?>';">Galeria</button>
        <button type="button" class="voltar" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
      </div>
    </form>
  </div>
</body>
</html>
