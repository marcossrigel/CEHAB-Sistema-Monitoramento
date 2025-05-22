<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php');
  exit;
}

include_once('config.php');

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;
$ids = $_POST['ids'] ?? [];

if (isset($_POST['salvar'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_iniciativa = intval($_GET['id_iniciativa']);
    $problemas = $_POST['problema'];
    $contramedidas = $_POST['contramedida'];
    $prazos = $_POST['prazo'];
    $responsaveis = $_POST['responsavel'];

    for ($i = 0; $i < count($problemas); $i++) {
    $id_existente = intval($ids[$i] ?? 0);
    $problema = mysqli_real_escape_string($conexao, $problemas[$i]);
    $contramedida = mysqli_real_escape_string($conexao, $contramedidas[$i]);
    $prazo = mysqli_real_escape_string($conexao, $prazos[$i]);
    $responsavel = mysqli_real_escape_string($conexao, $responsaveis[$i]);

      if ($id_existente > 0) {
          $query = "UPDATE pendencias SET problema='$problema', contramedida='$contramedida', prazo='$prazo', responsavel='$responsavel'
                    WHERE id=$id_existente AND id_usuario=$id_usuario";
      } else {
          $query = "INSERT INTO pendencias (id_usuario, id_iniciativa, problema, contramedida, prazo, responsavel) 
                    VALUES ('$id_usuario', '$id_iniciativa', '$problema', '$contramedida', '$prazo', '$responsavel')";
      }

      mysqli_query($conexao, $query);
    }
}
$dados_pendencias = mysqli_query($conexao, "SELECT * FROM pendencias WHERE id_usuario = ".$_SESSION['id_usuario']." AND id_iniciativa = $id_iniciativa");

$query_nome = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
$resultado_nome = mysqli_query($conexao, $query_nome);
$linha_nome = mysqli_fetch_assoc($resultado_nome);
$nome_iniciativa = $linha_nome['iniciativa'] ?? 'Iniciativa Desconhecida';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Planilha Web</title>
  <style>
    :root {
      --color-dark: #1d2129;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      font-family: 'Poppins', sans-serif;
      background: #e3e8ec;
      min-height: 100vh;
    }
    .table-container {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 8px 15px;
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
      min-width: 120px;
    }
    td[contenteditable] {
      width: 100%;
      display: block;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 8px;
      min-width: 120px;
    }
    td[contenteditable]:focus {
      outline: none;
      border: 1px solid #4da6ff;
      background-color: #f0f8ff;
    }
    input[type="text"], input[type="date"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
    }
    input[type="text"]:focus,
    input[type="date"]:focus {
      outline: none; 
      border: 1px solid #4da6ff; 
      background-color: #f0f8ff;
    }
    .main-title {
      font-size: 26px;
      color: var(--color-dark);
      text-align: center;
      margin-bottom: 20px;
    }
    .button-group {
      margin-top: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
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
    @media (max-width: 768px) {
      .main-title {
        font-size: 20px;
        padding: 0 10px;
      }
      table {
        font-size: 13px;
        display: block;
        overflow-x: auto;
      }
      th, td {
        padding: 6px;
      }
      td[contenteditable] {
        min-width: 90px;
        font-size: 13px;
      }
      .button-group {
        flex-direction: column;
        align-items: center;
      }
      .button-group button {
        width: 100%;
        max-width: 250px;
      }
    }
  </style>
</head>
<body>

<div class="table-container">
  <div class="main-title"><?php echo htmlspecialchars($nome_iniciativa); ?> - Acompanhamento de Pendências</div>

  <form method="post" action="acompanhamento.php?id_iniciativa=<?php echo $id_iniciativa; ?>">
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
      <?php while ($linha = mysqli_fetch_assoc($dados_pendencias)) { ?>
        
        <tr data-id="<?php echo $linha['id']; ?>">
          <td><input type="text" value="<?php echo htmlspecialchars($linha['problema']); ?>"></td>
          <td><input type="text" value="<?php echo htmlspecialchars($linha['contramedida']); ?>"></td>
          <td><input type="date" value="<?php echo htmlspecialchars($linha['prazo']); ?>"></td>
          <td><input type="text" value="<?php echo htmlspecialchars($linha['responsavel']); ?>"></td>
          <input type="hidden" name="ids[]" value="<?php echo $linha['id']; ?>">
        </tr>

      <?php } ?>

      </tbody>
    </table>
    
    <div class="button-group">
      <button type="button" onclick="addRow()">Adicionar Linha</button>
      <button type="button" onclick="deleteRow()">Excluir Linha</button>
      <button type="submit" name="salvar" id="submit" style="background-color:rgb(42, 179, 0);">Salvar</button>
      <button type="button" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
    </div>
  </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(event) {
  const form = this;
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const linhas = table.rows;

  let temLinhaValida = false;

  for (let i = 0; i < linhas.length; i++) {
    const linha = linhas[i];
    const id = linha.getAttribute('data-id');
    const cells = linha.cells;

    const campos = ['problema', 'contramedida', 'prazo', 'responsavel'];
    campos.forEach((campo, idx) => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = campo + '[]';

      const celula = cells[idx];
      const inputDentro = celula.querySelector('input');

      if (inputDentro) {
        input.value = inputDentro.value.trim();
      } else {
        input.value = celula.innerText.trim();
      }

      form.appendChild(input);
    });

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'ids[]';
    inputId.value = id ? id : '';
    form.appendChild(inputId);

    temLinhaValida = true;
  }

  if (!temLinhaValida) {
    event.preventDefault();
    alert('Nenhuma pendência válida para salvar!');
  }
});

function addRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();
  const campos = ['text', 'text', 'date', 'text']; // tipos dos inputs

  for (let i = 0; i < campos.length; i++) {
    const newCell = newRow.insertCell();
    const input = document.createElement('input');
    input.type = campos[i];
    newCell.appendChild(input);
  }
}

function deleteRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  if (table.rows.length > 0) {
    const lastRow = table.rows[table.rows.length - 1];
    const id = lastRow.getAttribute('data-id');
    if (id) {
      fetch('excluir_pendencia.php?id=' + id, { method: 'GET' })
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
