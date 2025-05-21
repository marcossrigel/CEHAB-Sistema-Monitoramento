<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php');
  exit;
}

include_once('config.php');

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

// Verifica se dados foram enviados
if (isset($_POST['etapa'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $etapa = $_POST['etapa'] ?? [];
    $inicio_previsto = $_POST['inicio_previsto'] ?? [];
    $termino_previsto = $_POST['termino_previsto'] ?? [];
    $inicio_real = $_POST['inicio_real'] ?? [];
    $termino_real = $_POST['termino_real'] ?? [];
    $evolutivo = $_POST['evolutivo'] ?? [];
    $ids = $_POST['ids'] ?? [];
    $tipo_etapa = $_POST['tipo_etapa'] ?? [];

    if (count($etapa) === 0) {
        echo "<p style='color:red;text-align:center'>Nenhuma linha foi enviada.</p>";
        exit;
    }

    for ($i = 0; $i < count($etapa); $i++) {
        $id_existente = intval($ids[$i] ?? 0);
        $etp = mysqli_real_escape_string($conexao, $etapa[$i]);
        $ini_prev = mysqli_real_escape_string($conexao, $inicio_previsto[$i]);
        $ter_prev = mysqli_real_escape_string($conexao, $termino_previsto[$i]);
        $ini_real = mysqli_real_escape_string($conexao, $inicio_real[$i]);
        $ter_real = mysqli_real_escape_string($conexao, $termino_real[$i]);
        $evo = mysqli_real_escape_string($conexao, $evolutivo[$i]);
        $tipo = mysqli_real_escape_string($conexao, isset($tipo_etapa[$i]) ? $tipo_etapa[$i] : 'linha');

        if ($id_existente > 0) {
            $query = "UPDATE marcos SET tipo_etapa='$tipo', etapa='$etp', inicio_previsto='$ini_prev', termino_previsto='$ter_prev',
                        inicio_real='$ini_real', termino_real='$ter_real', evolutivo='$evo'
                      WHERE id = $id_existente AND id_usuario = $id_usuario";
        } else {
            $query = "INSERT INTO marcos (id_usuario, id_iniciativa, tipo_etapa, etapa, inicio_previsto, termino_previsto, inicio_real, termino_real, evolutivo)
                      VALUES ('$id_usuario', '$id_iniciativa', '$tipo', '$etp', '$ini_prev', '$ter_prev', '$ini_real', '$ter_real', '$evo')";
        }

        mysqli_query($conexao, $query);
    }
}

// Recupera o nome da iniciativa
$query_nome = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
$resultado_nome = mysqli_query($conexao, $query_nome);
$linha_nome = mysqli_fetch_assoc($resultado_nome);
$nome_iniciativa = $linha_nome['iniciativa'] ?? 'Iniciativa Desconhecida';

// Recupera os marcos já cadastrados
$query_dados = "SELECT * FROM marcos WHERE id_usuario = {$_SESSION['id_usuario']} AND id_iniciativa = $id_iniciativa";
$dados = mysqli_query($conexao, $query_dados);

function formatarParaBrasileiro($valor) {
    return number_format((float)$valor, 2, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medições</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
      width: 95%;
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
      table-layout: fixed;          
    }
    th, td {
      text-align: left;
      padding: 10px;
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
    
    input[type="text"],
    input[type="date"] {
      height: 20px;
      padding: 4px 8px;
      font-size: 13px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-family: 'Poppins', sans-serif;
      width: 100%;
      box-sizing: border-box;
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
    textarea {
      resize: vertical; 
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
  <div class="main-title"><?php echo htmlspecialchars($nome_iniciativa); ?> - Cronograma de Marcos</div>
  <form method="post" action="cronogramamarcos.php?id_iniciativa=<?php echo $id_iniciativa; ?>">
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
          <td>
            <textarea name="etapa[]" rows="2" class="campo-etapa"><?php echo htmlspecialchars($linha['etapa']); ?></textarea>
          </td>

          <td><input type="date" name="inicio_previsto[]" value="<?php echo $linha['inicio_previsto']; ?>"></td>
          <td><input type="date" name="termino_previsto[]" value="<?php echo $linha['termino_previsto']; ?>"></td>
          <td><input type="date" name="inicio_real[]" value="<?php echo $linha['inicio_real']; ?>"></td>
          <td><input type="date" name="termino_real[]" value="<?php echo $linha['termino_real']; ?>"></td>
          <td><input type="number" name="evolutivo[]" value="<?php echo $linha['evolutivo']; ?>" min="0" max="100" step="0.1" placeholder="0 a 100%"></td>
        </tr>
      <?php } ?>
      </tbody>

    </table>
    <div class="button-group">
      <button type="button" onclick="addTitleRow()">Adicionar Título</button>
      <button type="button" onclick="addRow()">Adicionar Linha</button>
      <button type="button" onclick="deleteRow()">Excluir Linha</button>
      <button type="submit" name="salvar" id="submit" style="background-color:rgb(42, 179, 0);">Salvar</button>
      <button type="button" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
    </div>
  </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(event) {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const linhas = table.rows;
  let temLinhaValida = false;

  for (let i = 0; i < linhas.length; i++) {
    const linha = linhas[i];
    const id = linha.getAttribute('data-id');
    const cells = linha.cells;

    if (id) continue;

    const etapaField = cells[0].querySelector('textarea, input');
    const campos = [
      etapaField ? etapaField.value.trim() : '',
      cells[1].querySelector('input')?.value.trim() || '',
      cells[2].querySelector('input')?.value.trim() || '',
      cells[3].querySelector('input')?.value.trim() || '',
      cells[4].querySelector('input')?.value.trim() || '',
      cells[5].querySelector('input')?.value.trim() || ''
    ];

    const linhaEstaVazia = campos.every(c => c === '');
    if (linhaEstaVazia) continue;

    const nomesCampos = [
      'etapa',
      'inicio_previsto',
      'termino_previsto',
      'inicio_real',
      'termino_real',
      'evolutivo'
    ];

    nomesCampos.forEach((campo, idx) => {
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = campo + '[]';

  if (campo.includes('valor') || campo === 'bm') {
    input.value = converterParaFloatBrasileiro(campos[idx]);
  } else if (campo.includes('data')) {
    input.value = converterParaDataISO(campos[idx]);
  } else {
    input.value = campos[idx];
  }

      this.appendChild(input);
    });

    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'ids[]';
    inputId.value = id ? id : '';
    this.appendChild(inputId);

    const inputTipo = document.createElement('input');
    inputTipo.type = 'hidden';
    inputTipo.name = 'tipo_etapa[]';
    const tipo = etapaField?.placeholder === 'Título' ? 'subtitulo' : 'linha';
    inputTipo.value = tipo;
    this.appendChild(inputTipo);

    temLinhaValida = true;
  }

  if (!temLinhaValida) {
    event.preventDefault();
    alert('Nenhuma medicao valida para salvar!');
  }
});

function addTitleRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();

  const campos = ['etapa', 'inicio_previsto', 'termino_previsto', 'inicio_real', 'termino_real', 'evolutivo'];

  campos.forEach((campo, index) => {
    const cell = newRow.insertCell();


    if (index === 0) {
      const input = document.createElement('input');
      input.type = 'text';
      input.name = campo + '[]';
      input.placeholder = 'Título';
      input.style.width = '100%';
      input.style.fontFamily = "'Poppins', sans-serif";
      input.style.fontSize = '13px';
      input.style.padding = '4px 8px';
      input.style.border = '1px solid #ccc';
      input.style.borderRadius = '6px';
      input.style.boxSizing = 'border-box';
      cell.appendChild(input);
    }
    else {
      const input = document.createElement('input');
      if (campo === 'evolutivo') {
        input.type = 'number';
        input.min = '0';
        input.max = '100';
        input.step = '0.1';
        input.placeholder = '0 a 100%';
      } else {
        input.type = 'date';
      }
      input.name = campo + '[]';
      input.style.width = '100%';
      input.style.opacity = '0.9';
      input.style.border = 'none';
      input.style.borderRadius = '6px';
      input.style.height = '20px';
      input.style.padding = '4px 8px';
      input.style.boxSizing = 'border-box';
      cell.appendChild(input);
    }
  });
}

function addRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();
  const campos = ['etapa', 'inicio_previsto', 'termino_previsto', 'inicio_real', 'termino_real', 'evolutivo'];

  campos.forEach((campo, index) => {
    const cell = newRow.insertCell();

    if (index === 0) {
      const textarea = document.createElement('textarea');
      textarea.name = campo + '[]';
      textarea.rows = 2;
      textarea.className = 'campo-etapa';
      textarea.style.width = '100%';
      textarea.style.fontFamily = "'Poppins', sans-serif";
      textarea.style.fontSize = '13px';
      textarea.style.padding = '4px 8px';
      textarea.style.border = '1px solid #ccc';
      textarea.style.borderRadius = '6px';
      textarea.style.boxSizing = 'border-box';
      textarea.style.resize = 'vertical';
      cell.appendChild(textarea);
    } 
    else {
      const input = document.createElement('input');
      if (campo === 'evolutivo') {
        input.type = 'number';
        input.min = '0';
        input.max = '100';
        input.step = '0.1';
        input.placeholder = '0 a 100%';
      } else {
        input.type = 'date';
      }
      input.name = campo + '[]';
      input.style.width = '100%';
      input.style.font = 'inherit';
      input.style.border = '1px solid #ccc';
      input.style.borderRadius = '6px';
      input.style.height = '20px';
      input.style.padding = '4px 8px';
      input.style.boxSizing = 'border-box';
      cell.appendChild(input);
    }
  });
}

function deleteRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const lastRow = table.rows[table.rows.length - 1];

  if (!lastRow) return;

  const id = lastRow.getAttribute('data-id');

  if (id) {
    fetch(`excluir_linha.php?id=${id}`, { method: 'GET' })
      .then(response => {
        if (!response.ok) throw new Error("Erro ao excluir do banco");
        return response.text();
      })
      .then(data => {
        console.log(data);
        table.deleteRow(-1);
      })
      .catch(error => {
        alert("Erro ao excluir no servidor.");
        console.error(error);
      });
  } 
  else {
    table.deleteRow(-1);
  }
}

function converterParaFloatBrasileiro(valor) {
  return valor.replace(/\./g, '').replace(',', '.');
}

function converterParaDataISO(dataBR) {
  if (!dataBR.includes('/')) return dataBR;
  const partes = dataBR.split('/');
  if (partes.length === 3) {
    return `${partes[2]}-${partes[1]}-${partes[0]}`;
  }
  return dataBR;
}

</script>
</body>
</html>
