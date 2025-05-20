<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php');
  exit;
}

include("config.php");

$id_usuario = $_SESSION['id_usuario'];
$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

if ($id_iniciativa === 0) {
  die("ID da iniciativa inválido.");
}

// Exclusão (usada por JavaScript via fetch)
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  mysqli_query($conexao, "DELETE FROM marcos WHERE id = $id");
  echo "OK";
  exit;
}

// Salvamento
if (isset($_POST['salvar'])) {
  $tipo_etapa = $_POST['tipo_etapa'];
  $etapa = $_POST['etapa'];
  $inicio_previsto = $_POST['inicio_previsto'];
  $termino_previsto = $_POST['termino_previsto'];
  $inicio_real = $_POST['inicio_real'];
  $termino_real = $_POST['termino_real'];
  $evolutivo = $_POST['evolutivo'];
  $ids = $_POST['ids'];

  for ($i = 0; $i < count($etapa); $i++) {
    $id_existente = intval($ids[$i]);
    $tipo = mysqli_real_escape_string($conexao, $tipo_etapa[$i]);
    $etp = mysqli_real_escape_string($conexao, $etapa[$i]);
    $ini_prev = mysqli_real_escape_string($conexao, $inicio_previsto[$i]);
    $ter_prev = mysqli_real_escape_string($conexao, $termino_previsto[$i]);
    $ini_real = mysqli_real_escape_string($conexao, $inicio_real[$i]);
    $ter_real = mysqli_real_escape_string($conexao, $termino_real[$i]);
    $evo = mysqli_real_escape_string($conexao, $evolutivo[$i]);

    if ($id_existente > 0) {
      $query = "UPDATE marcos SET tipo_etapa='$tipo', etapa='$etp', inicio_previsto='$ini_prev',
                termino_previsto='$ter_prev', inicio_real='$ini_real', termino_real='$ter_real',
                evolutivo='$evo' WHERE id = $id_existente AND id_usuario = $id_usuario";
    } else {
      $query = "INSERT INTO marcos (id_usuario, id_iniciativa, tipo_etapa, etapa, inicio_previsto, 
                termino_previsto, inicio_real, termino_real, evolutivo) VALUES (
                '$id_usuario', '$id_iniciativa', '$tipo', '$etp', '$ini_prev', '$ter_prev', 
                '$ini_real', '$ter_real', '$evo')";
    }

    mysqli_query($conexao, $query);
  }
}

// Carregar dados
$dados = mysqli_query($conexao, "SELECT * FROM marcos WHERE id_usuario = $id_usuario AND id_iniciativa = $id_iniciativa");

// Nome da iniciativa
$query_nome = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
$resultado_nome = mysqli_query($conexao, $query_nome);
$linha_nome = mysqli_fetch_assoc($resultado_nome);
$nome_iniciativa = $linha_nome['iniciativa'] ?? 'Iniciativa Desconhecida';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?php echo $nome_iniciativa; ?> - CRONOGRAMA DE MARCOS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #e3e8ec;
    }
    .table-container {
      max-width: 1100px;
      margin: 40px auto;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    input[type="text"],
    input[type="date"] {
      width: 100%;
      padding: 6px 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-family: 'Poppins', sans-serif;
    }
    .button-group {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
      flex-wrap: wrap;
    }
    .button-group button {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      color: white;
      cursor: pointer;
    }
    .button-group button:nth-child(1),
    .button-group button:nth-child(2),
    .button-group button:nth-child(3) {
      background-color: #4da6ff;
    }
    .button-group button:nth-child(3) {
      background-color: green;
    }
    .button-group button:hover {
      opacity: 0.9;
    }
    .main-title {
      text-align: center;
      font-size: 24px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="table-container">
  <div class="main-title"><?php echo htmlspecialchars($nome_iniciativa); ?> - CRONOGRAMA DE MARCOS</div>
  <form method="post" action="?id_iniciativa=<?php echo $id_iniciativa; ?>">
    <table id="spreadsheet">
      <thead>
        <tr>
          <th>Etapa</th>
          <th>Início Previsto</th>
          <th>Término Previsto</th>
          <th>Início Real</th>
          <th>Término Real</th>
          <th>% Evolutivo</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($linha = mysqli_fetch_assoc($dados)) { ?>
        <tr data-id="<?php echo $linha['id']; ?>">
          <td contenteditable="true"><?php echo htmlspecialchars($linha['etapa']); ?></td>
          <td><input type="date" name="inicio_previsto[]" value="<?php echo $linha['inicio_previsto']; ?>"></td>
          <td><input type="date" name="termino_previsto[]" value="<?php echo $linha['termino_previsto']; ?>"></td>
          <td><input type="date" name="inicio_real[]" value="<?php echo $linha['inicio_real']; ?>"></td>
          <td><input type="date" name="termino_real[]" value="<?php echo $linha['termino_real']; ?>"></td>
          <td contenteditable="true"><?php echo htmlspecialchars($linha['evolutivo']); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <div class="button-group">
      <button type="button" onclick="addRow()">Adicionar Linha</button>
      <button type="button" onclick="deleteRow()">Excluir Linha</button>
      <button type="submit" name="salvar">Salvar</button>
      <button type="button" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
    </div>
  </form>
</div>

<script>
function addRow() {
  const tbody = document.querySelector('#spreadsheet tbody');
  const row = tbody.insertRow();
  for (let i = 0; i < 6; i++) {
    const cell = row.insertCell();
    if (i === 1 || i === 2 || i === 3 || i === 4) {
      const input = document.createElement('input');
      input.type = 'date';
      input.name = ['inicio_previsto[]', 'termino_previsto[]', 'inicio_real[]', 'termino_real[]'][i - 1];
      cell.appendChild(input);
    } else {
      cell.contentEditable = true;
    }
  }
}

function deleteRow() {
  const table = document.querySelector('#spreadsheet tbody');
  const lastRow = table.rows[table.rows.length - 1];
  if (!lastRow) return;
  const id = lastRow.getAttribute('data-id');
  if (id) {
    fetch(`cronogramamarcos.php?id=${id}`, { method: 'GET' })
      .then(r => r.text())
      .then(msg => {
        console.log(msg);
        table.deleteRow(-1);
      });
  } else {
    table.deleteRow(-1);
  }
}
</script>
</body>
</html>
