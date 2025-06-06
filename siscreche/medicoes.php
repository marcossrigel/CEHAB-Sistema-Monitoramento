<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php');
  exit;
}

include_once('config.php');

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

if (isset($_POST['salvar'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $valor_orcamento = $_POST['valor_orcamento'];
    $valor_bm = $_POST['valor_bm'];
    $saldo_obra = $_POST['saldo_obra'];
    $bm = $_POST['bm'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $ids = isset($_POST['ids']) ? $_POST['ids'] : [];

    function formatarDinheiro($valor) {
        return str_replace(',', '.', str_replace('.', '', $valor));
    }

    for ($i = 0; $i < count($valor_orcamento); $i++) {
        $id_existente = isset($ids[$i]) ? intval($ids[$i]) : 0;
        $valor_orcamento_item = mysqli_real_escape_string($conexao, formatarDinheiro($valor_orcamento[$i]));
        $valor_bm_item = mysqli_real_escape_string($conexao, formatarDinheiro($valor_bm[$i]));
        $saldo_obra_item = mysqli_real_escape_string($conexao, formatarDinheiro($saldo_obra[$i]));
        $bm_item = mysqli_real_escape_string($conexao, formatarDinheiro($bm[$i]));
        $data_inicio_item = mysqli_real_escape_string($conexao, $data_inicio[$i]);
        $data_fim_item = mysqli_real_escape_string($conexao, $data_fim[$i]);
        $data_registro_item = date('Y-m-d H:i:s');

        if ($id_existente > 0) {
            $query = "UPDATE medicoes SET 
                        valor_orcamento = '$valor_orcamento_item',
                        valor_bm = '$valor_bm_item',
                        saldo_obra = '$saldo_obra_item',
                        bm = '$bm_item',
                        data_inicio = '$data_inicio_item',
                        data_fim = '$data_fim_item'
                      WHERE id = $id_existente AND id_usuario = $id_usuario";
        } else {
            $query = "INSERT INTO medicoes (id_usuario, id_iniciativa, valor_orcamento, valor_bm, saldo_obra, bm, data_inicio, data_fim, data_registro) 
                      VALUES ('$id_usuario', '$id_iniciativa', '$valor_orcamento_item', '$valor_bm_item', '$saldo_obra_item', '$bm_item', '$data_inicio_item', '$data_fim_item', '$data_registro_item')";
        }

        mysqli_query($conexao, $query);
    }
}

$dados = mysqli_query($conexao, "SELECT * FROM medicoes WHERE id_usuario = ".$_SESSION['id_usuario']." AND id_iniciativa = $id_iniciativa");

$query_nome = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
$resultado_nome = mysqli_query($conexao, $query_nome);
$linha_nome = mysqli_fetch_assoc($resultado_nome);
$nome_iniciativa = $linha_nome['iniciativa'] ?? 'Iniciativa Desconhecida';

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
      width: 90%;                  
      max-width: 1200px;            
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
  <div class="main-title"><?php echo htmlspecialchars($nome_iniciativa); ?> - Acompanhamento de Medições</div>
  <form method="post" action="medicoes.php?id_iniciativa=<?php echo $id_iniciativa; ?>">
    <table id="spreadsheet">
      <thead>
        <tr>
          <th>Valor Total da Obra</th>
          <th>Valor BM</th>
          <th>Saldo da Obra</th>
          <th>BM</th>
          <th>Data Início</th>
          <th>Data Fim</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($linha = mysqli_fetch_assoc($dados)) { ?>
        <tr data-id="<?php echo $linha['id']; ?>">
          <td contenteditable="true"><?php echo formatarParaBrasileiro($linha['valor_orcamento']); ?></td>
          <td contenteditable="true"><?php echo formatarParaBrasileiro($linha['valor_bm']); ?></td>
          <td contenteditable="true"><?php echo formatarParaBrasileiro($linha['saldo_obra']); ?></td>
          <td contenteditable="true"><?php echo formatarParaBrasileiro($linha['bm']); ?></td>
          <td><input type="date" name="data_inicio[]" value="<?php echo htmlspecialchars($linha['data_inicio']); ?>" /></td>
          <td><input type="date" name="data_fim[]" value="<?php echo htmlspecialchars($linha['data_fim']); ?>" /></td>
          
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
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const linhas = table.rows;
  let temLinhaValida = false;

  for (let i = 0; i < linhas.length; i++) {
    const linha = linhas[i];
    const id = linha.getAttribute('data-id');
    const cells = linha.cells;

    const campos = [
      cells[0]?.innerText.trim(),
      cells[1]?.innerText.trim(),
      cells[2]?.innerText.trim(),
      cells[3]?.innerText.trim(),
      cells[4]?.innerText.trim(),
      cells[5]?.innerText.trim(),
    ];

    const nomesCampos = [
      'valor_orcamento',
      'valor_bm',
      'saldo_obra',
      'bm',
      'data_inicio',
      'data_fim',
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

    temLinhaValida = true;
  }

  if (!temLinhaValida) {
    event.preventDefault();
    alert('Nenhuma medicao valida para salvar!');
  }
});

function addRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();

  for (let i = 0; i < 6; i++) {
    const newCell = newRow.insertCell();

    if (i === 4 || i === 5) {
      const input = document.createElement('input');
      input.type = 'date';
      input.name = i === 4 ? 'data_inicio[]' : 'data_fim[]';
      input.style.border = 'none';
      input.style.width = '100%';
      input.style.font = 'inherit';
      input.style.background = 'transparent';
      newCell.appendChild(input);
    } else {
      newCell.contentEditable = "true";
    }
  }
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
  } else {
    table.deleteRow(-1);
  }
}

function converterParaFloatBrasileiro(valor) {
  return valor.replace(/[R$\s]/g, '').replace(/\./g, '').replace(',', '.');
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