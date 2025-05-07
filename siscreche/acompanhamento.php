<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include_once('config.php');

if (isset($_POST['salvar'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $problemas = $_POST['problema'];
    $contramedidas = $_POST['contramedida'];
    $prazos = $_POST['prazo'];
    $responsaveis = $_POST['responsavel'];

    for ($i = 0; $i < count($problemas); $i++) {
        $problema = mysqli_real_escape_string($conexao, $problemas[$i]);
        $contramedida = mysqli_real_escape_string($conexao, $contramedidas[$i]);
        $prazo = mysqli_real_escape_string($conexao, $prazos[$i]);
        $responsavel = mysqli_real_escape_string($conexao, $responsaveis[$i]);

        $query = "INSERT INTO pendencias (id_usuario, problema, contramedida, prazo, responsavel) 
                  VALUES ('$id_usuario', '$problema', '$contramedida', '$prazo', '$responsavel')";

        mysqli_query($conexao, $query);
    }

    echo "<script>alert('Pendências salvas com sucesso!'); window.location.href='visualizar.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="acompanhamento.php">
  <title>Planilha Web</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #e3e8ec;;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .table-container {
      background: #fff;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      min-width: 600px;
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 15px;
    }
    th, td {
      text-align: left;
      padding: 10px;
    }
    th {
      padding-bottom: 15px;
    }
    td {
      padding-right: 15px;
    }
    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
    }
    input[type="date"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
    }
    .main-title {
      font-size: 32px;
      color: var(--color-dark);
      text-align: center;
      margin-bottom: 20px;
    }
    .button-group {
      margin-top: 20px;
      display: flex;
      justify-content: space-around;
      gap: 10px;
    }
    .button-group button {
      padding: 10px 20px;
      background-color: #4da6ff;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .button-group button:hover {
      background-color: #3399ff;
    }
  </style>
</head>
<body>

<div class="table-container">
  <div class="main-title">Acompanhamento de Pendências</div>
  <form method="post" action="acompanhamento.php">
  <table id="spreadsheet">
      <thead>
        <tr>
          <th>Problema</th>
          <th>Contramedida</th>
          <th>Prazo</th>
          <th>Responsável</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" name="problema[]" placeholder=""></td>
          <td><input type="text" name="contramedida[]" placeholder=""></td>
          <td><input type="date" name="prazo[]" placeholder=""></td>
          <td><input type="text" name="responsavel[]" placeholder=""></td>
        </tr>
      </tbody>
    </table>
    <div class="button-group">
      <button type="button" onclick="addRow()">Adicionar Linha</button>
      <button type="button" onclick="deleteRow()">Excluir Linha</button>
      <button type="submit" name="salvar" id="submit">Salvar</button>
      <button onclick="window.location.href='visualizar.php';">< Voltar</button>
    </div>

  </form>

</div>

<script>
  function addRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();

  const campos = ['problema[]', 'contramedida[]', 'prazo[]', 'responsavel[]'];
  const tipos = ['text', 'text', 'date', 'text'];

  for (let i = 0; i < campos.length; i++) {
    const newCell = newRow.insertCell();
    const input = document.createElement('input');
    input.type = tipos[i];
    input.name = campos[i];
    newCell.appendChild(input);
  }
}

  function deleteRow() {
    const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
    if (table.rows.length > 1) {
      table.deleteRow(-1);
    }
  }
</script>

</body>
</html>
