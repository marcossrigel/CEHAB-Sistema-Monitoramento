<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include_once('config.php');

$id_usuario = $_SESSION['id_usuario'];
$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

$titulo_iniciativa = '';

if ($id_iniciativa > 0) {
    $resultado = mysqli_query($conexao, "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa");
    if ($linha = mysqli_fetch_assoc($resultado)) {
        $titulo_iniciativa = $linha['iniciativa'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['etapa'])) {
        foreach ($_POST['etapa'] as $index => $etapa) {
            $inicio_previsto = !empty($_POST['inicio_previsto'][$index]) ? "'".mysqli_real_escape_string($conexao, $_POST['inicio_previsto'][$index])."'" : "NULL";
            $termino_previsto = !empty($_POST['termino_previsto'][$index]) ? "'".mysqli_real_escape_string($conexao, $_POST['termino_previsto'][$index])."'" : "NULL";
            $inicio_real = !empty($_POST['inicio_real'][$index]) ? "'".mysqli_real_escape_string($conexao, $_POST['inicio_real'][$index])."'" : "NULL";
            $termino_real = !empty($_POST['termino_real'][$index]) ? "'".mysqli_real_escape_string($conexao, $_POST['termino_real'][$index])."'" : "NULL";
            $evolutivo         = mysqli_real_escape_string($conexao, $_POST['evolutivo'][$index]);

            $etapa = mysqli_real_escape_string($conexao, $etapa);
            $tipo_etapa = isset($_POST['tipo_etapa'][$index]) ? $_POST['tipo_etapa'][$index] : 'linha';

            $query = "INSERT INTO marcos (id_usuario, id_iniciativa, etapa, inicio_previsto, termino_previsto, inicio_real, termino_real, evolutivo, tipo_etapa) 
              VALUES ('$id_usuario', '$id_iniciativa', '$etapa', $inicio_previsto, $termino_previsto, $inicio_real, $termino_real, '$evolutivo', '$tipo_etapa')";
 
            mysqli_query($conexao, $query);
        }
    }
}

$dados = mysqli_query($conexao, "SELECT * FROM marcos WHERE id_usuario = $id_usuario AND id_iniciativa = $id_iniciativa");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }
    .container {
      width: 100%;
      max-width: 1200px;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    h1 {
      margin-bottom: 20px;
      font-size: 24px;
      text-align: center;
      font-weight: 400;
      color: #2c2c2c;
    }
    table {
      min-width: 700px;
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .tabela-scroll {
      width: 100%;
      overflow-x: auto;
    }
    th, td {
      padding: 12px;
      border: 1px solid #dee2e6;
      text-align: left;
      vertical-align: top;
    }
    th {
      background-color: #f1f3f5;
    }
    td.etapa {
      width: 25%;
    }
    textarea.no-border {
      width: calc(100% - 10px);
      height: 60px;
      resize: vertical;
      border: none;
      outline: none;
      font-family: 'Arial', sans-serif;
      font-size: 14px;
      padding: 5px;
    }
    input {
      width: calc(100% - 10px);
      border: 1px solid #ccc;
      padding: 6px;
      border-radius: 4px;
      box-sizing: border-box;
    }
    .subtitle-row input {
      font-weight: 600;
      background-color: #f8f9fa;
      color: #495057;
    }

    .button-group {
      flex-wrap: wrap;
      display: flex;
      justify-content: center;
      gap: 10px;
    }
    .button-group button {
      background-color: #339af0;
      color: #fff;
      border: none;
      padding: 9px;
      border-radius: 10px;
      font-size: 13px;
      flex: 1 1 45%;
      max-width: 140px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .button-group button:hover {
      background-color: #228be6;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><?php echo htmlspecialchars($titulo_iniciativa); ?> - CRONOGRAMA DE MARCOS</h1>
    
  <form method="post">
  <div class="tabela-scroll">
    <table id="cronogramaTable">
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
        <?php while($row = mysqli_fetch_assoc($dados)): ?>
        <tr data-id="<?php echo $row['id']; ?>" class="<?php echo $row['tipo_etapa'] === 'subtitulo' ? 'subtitle-row' : ''; ?>">
          <td>
            <?php if ($row['tipo_etapa'] === 'subtitulo'): ?>
              <input type="text" value="<?php echo htmlspecialchars($row['etapa']); ?>" readonly>
            <?php else: ?>
              <textarea class="no-border" readonly><?php echo htmlspecialchars($row['etapa']); ?></textarea>
            <?php endif; ?>
          </td>
          <td><input type="date" value="<?php echo $row['inicio_previsto']; ?>" readonly></td>
          <td><input type="date" value="<?php echo $row['termino_previsto']; ?>" readonly></td>
          <td><input type="date" value="<?php echo $row['inicio_real']; ?>" readonly></td>
          <td><input type="date" value="<?php echo $row['termino_real']; ?>" readonly></td>
          <td><input type="text" value="<?php echo $row['evolutivo']; ?>" readonly></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

    <div class="button-group">
      <button type="button" onclick="adicionarLinha()">Adicionar Linha</button>
      <button type="button" onclick="adicionarSubtitulo()">Adicionar Subtítulo</button>
      <button type="button" onclick="excluirLinha()">Excluir Linha</button>
      <button type="submit" name="salvar" style="background-color:rgb(42, 179, 0);">Salvar</button>
      <button type="button" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
    </div>
    
  </form>
  

  <script>
    function adicionarLinha(classeExtra = '') {
      const table = document.getElementById('cronogramaTable').getElementsByTagName('tbody')[0];
      const newRow = table.insertRow();
      if (classeExtra) newRow.classList.add(classeExtra);

      const etapaCell = newRow.insertCell(0);
      etapaCell.innerHTML = classeExtra === 'subtitle-row'
      ? '<input type="text" name="etapa[]">'
      : '<textarea class="no-border" name="etapa[]"></textarea>';

      const tipoEtapa = classeExtra === 'subtitle-row' ? 'subtitulo' : 'linha';
      etapaCell.innerHTML += `<input type="hidden" name="tipo_etapa[]" value="${tipoEtapa}">`;

      const campos = ['inicio_previsto', 'termino_previsto', 'inicio_real', 'termino_real', 'evolutivo'];
      for (let i = 0; i < campos.length; i++) {
        const tipoCampo = campos[i] !== 'evolutivo' ? 'date' : 'text';
        const cell = newRow.insertCell(i + 1);
        cell.innerHTML = `<input type="${tipoCampo}" name="${campos[i]}[]">`;
      }
    }

    function adicionarSubtitulo() {
      adicionarLinha('subtitle-row');
    }

    function excluirLinha() {
      const table = document.getElementById('cronogramaTable').getElementsByTagName('tbody')[0];
      if (table.rows.length > 0) {
        const lastRow = table.rows[table.rows.length - 1];
        const id = lastRow.getAttribute('data-id');

        if (id) {
          fetch('excluir_cronograma.php?id=' + id, { method: 'GET' })
            .then(response => response.text())
            .then(data => {
              console.log(data);
              table.deleteRow(-1);
            });
        } else {
          table.deleteRow(-1);
        }
      }
    }
  </script>

</body>
</html>