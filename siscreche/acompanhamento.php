<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php');
  exit;
}

include_once('config.php');

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

if (isset($_POST['salvar'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_iniciativa = intval($_GET['id_iniciativa']);
    $problemas = $_POST['problema'];
    $contramedidas = $_POST['contramedida'];
    $prazos = $_POST['prazo'];
    $responsaveis = $_POST['responsavel'];


    for ($i = 0; $i < count($problemas); $i++) {
        $problema = mysqli_real_escape_string($conexao, $problemas[$i]);
        $contramedida = mysqli_real_escape_string($conexao, $contramedidas[$i]);
        $prazo = mysqli_real_escape_string($conexao, $prazos[$i]);
        $responsavel = mysqli_real_escape_string($conexao, $responsaveis[$i]);

        $query = "INSERT INTO pendencias (id_usuario, id_iniciativa, problema, contramedida, prazo, responsavel) 
                  VALUES ('$id_usuario', '$id_iniciativa', '$problema', '$contramedida', '$prazo', '$responsavel')";

        mysqli_query($conexao, $query);
    }

}
$dados_pendencias = mysqli_query($conexao, "SELECT * FROM pendencias WHERE id_usuario = ".$_SESSION['id_usuario']." AND id_iniciativa = $id_iniciativa");

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
    }
    td[contenteditable]:focus {
      outline: none;
    }
    td[contenteditable] {
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
          <td contenteditable="true"><?php echo htmlspecialchars($linha['problema']); ?></td>
          <td contenteditable="true"><?php echo htmlspecialchars($linha['contramedida']); ?></td>
          <td contenteditable="true"><?php echo htmlspecialchars($linha['prazo']); ?></td>
          <td contenteditable="true"><?php echo htmlspecialchars($linha['responsavel']); ?></td>
        </tr>
      <?php } ?>
    </tbody>

    </table>
    <div class="button-group">
      <button type="button" onclick="addRow()">Adicionar Linha</button>
      <button type="button" onclick="deleteRow()">Excluir Linha</button>
      <button type="submit" name="salvar" id="submit">Salvar</button>
      <button type="button" onclick="window.location.href='visualizar.php';">< Voltar</button>
    </div>

  </form>

</div>

<script>

document.querySelector('form').addEventListener('submit', function(event) {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const linhas = table.rows;

  for (let i = 0; i < linhas.length; i++) {
  const linha = linhas[i];
  const id = linha.getAttribute('data-id');

  if (!id) {
    const cells = linha.cells;

    const problema = document.createElement('input');
    problema.type = 'hidden';
    problema.name = 'problema[]';
    problema.value = cells[0].innerText.trim();
    this.appendChild(problema);

    const contramedida = document.createElement('input');
    contramedida.type = 'hidden';
    contramedida.name = 'contramedida[]';
    contramedida.value = cells[1].innerText.trim();
    this.appendChild(contramedida);

    const prazo = document.createElement('input');
    prazo.type = 'hidden';
    prazo.name = 'prazo[]';
    prazo.value = cells[2].innerText.trim();
    this.appendChild(prazo);

    const responsavel = document.createElement('input');
    responsavel.type = 'hidden';
    responsavel.name = 'responsavel[]';
    responsavel.value = cells[3].innerText.trim();
    this.appendChild(responsavel);
  }
}
  localStorage.removeItem('tabelaPendencias');
});

document.querySelector('form').addEventListener('submit', function(event) {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const linhas = table.rows;
  let temNovaLinha = false;

  for (let i = 0; i < linhas.length; i++) {
    const linha = linhas[i];
    const id = linha.getAttribute('data-id');
    const cells = linha.cells;

    if (!id) {
      const problema = cells[0].innerText.trim();
      const contramedida = cells[1].innerText.trim();
      const prazo = cells[2].innerText.trim();
      const responsavel = cells[3].innerText.trim();

      if (problema || contramedida || prazo || responsavel) {
        temNovaLinha = true;
        ['problema', 'contramedida', 'prazo', 'responsavel'].forEach((campo, idx) => {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = campo + '[]';
          input.value = cells[idx].innerText.trim();
          this.appendChild(input);
        });
      }
    }
  }

  if (!temNovaLinha) {
    event.preventDefault(); // impede o envio
    alert('Nenhuma nova pendência para salvar!');
  } else {
    localStorage.removeItem('tabelaPendencias');
  }
});


function addRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();

  for (let i = 0; i < 4; i++) {
    const newCell = newRow.insertCell();
    newCell.contentEditable = "true";
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
    } 
    else {
      table.deleteRow(-1);
    }
  }
}

</script>

</body>
</html>
