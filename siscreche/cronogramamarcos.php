<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);



include_once('config.php');
mysqli_set_charset($conexao, "utf8mb4");

$id_iniciativa = (int) ($_GET['id_iniciativa'] ?? 0);
$id_usuario = (int) ($_SESSION['id_usuario'] ?? 0);

if (isset($_POST['etapa'])) {
    $id_etapa_custom = $_POST['id_etapa_custom'] ?? [];
    $etapa = $_POST['etapa'] ?? [];
    $inicio_previsto = $_POST['inicio_previsto'] ?? [];
    $termino_previsto = $_POST['termino_previsto'] ?? [];
    $inicio_real = $_POST['inicio_real'] ?? [];
    $termino_real = $_POST['termino_real'] ?? [];
    $evolutivo = $_POST['evolutivo'] ?? [];
    $ids = $_POST['ids'] ?? [];
    $tipo_etapa = $_POST['tipo_etapa'] ?? [];

    for ($i = 0; $i < count($etapa); $i++) {
        $etapa_custom = "'" . mysqli_real_escape_string($conexao, trim($id_etapa_custom[$i] ?? '')) . "'";

        $id_existente = intval($ids[$i] ?? 0);
        $etp = mysqli_real_escape_string($conexao, $etapa[$i]);

        $ini_prev = trim($inicio_previsto[$i]) !== '' ? "'" . mysqli_real_escape_string($conexao, $inicio_previsto[$i]) . "'" : "NULL";
        $ter_prev = trim($termino_previsto[$i]) !== '' ? "'" . mysqli_real_escape_string($conexao, $termino_previsto[$i]) . "'" : "NULL";
        $ini_real = trim($inicio_real[$i]) !== '' ? "'" . mysqli_real_escape_string($conexao, $inicio_real[$i]) . "'" : "NULL";
        $ter_real = trim($termino_real[$i]) !== '' ? "'" . mysqli_real_escape_string($conexao, $termino_real[$i]) . "'" : "NULL";

        $evo_raw = trim($evolutivo[$i]);
        $evo = $evo_raw !== '' ? "'" . mysqli_real_escape_string($conexao, $evo_raw) . "'" : "NULL";

        $tipo = mysqli_real_escape_string($conexao, $tipo_etapa[$i] ?? 'linha');

        if ($id_existente > 0) {
            $query = "UPDATE marcos SET 
              tipo_etapa='$tipo',
              etapa='$etp',
              id_etapa_custom=$etapa_custom,
              inicio_previsto=$ini_prev,
              termino_previsto=$ter_prev,
              inicio_real=$ini_real,
              termino_real=$ter_real,
              evolutivo=$evo
            WHERE id = $id_existente AND id_usuario = $id_usuario";
        } else {
           $query = "INSERT INTO marcos (
              id_usuario, id_iniciativa, id_etapa_custom, tipo_etapa, etapa,
              inicio_previsto, termino_previsto, inicio_real, termino_real, evolutivo
            ) VALUES (
              '$id_usuario', '$id_iniciativa', $etapa_custom, '$tipo', '$etp',
              $ini_prev, $ter_prev, $ini_real, $ter_real, $evo
            )";
        }

        if (!mysqli_query($conexao, $query)) {
            echo "Erro: " . mysqli_error($conexao);
            exit;
        }
    }
}

$query_nome = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
$resultado_nome = mysqli_query($conexao, $query_nome);
$linha_nome = mysqli_fetch_assoc($resultado_nome);
$nome_iniciativa = $linha_nome['iniciativa'] ?? 'Iniciativa Desconhecida';

$query_dados = "SELECT * FROM marcos WHERE id_usuario = $id_usuario AND id_iniciativa = $id_iniciativa";
$dados = mysqli_query($conexao, $query_dados);

function formatarParaBrasileiro($valor) {
    return number_format((float)$valor, 2, ',', '.');
}
?>
<link href="assets/css/cronogramamarcos.css" rel="stylesheet">

<div class="table-container">
  <div class="main-title"><?php echo htmlspecialchars($nome_iniciativa); ?> - Cronograma de Marcos</div>
  <form method="post" action="cronogramamarcos.php?id_iniciativa=<?php echo $id_iniciativa; ?>">
    <table id="spreadsheet">
      <thead>
        <tr>
          <th style="width: 65px;">ID</th>
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
          
          <td style="max-width:50px;">
            
          <input type="text" name="id_etapa_custom[]" value="<?php echo htmlspecialchars($linha['id_etapa_custom'] ?? ''); ?>"
            style="width: 60px; font-size: 13px; padding: 4px 6px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; text-align: center;">
          </td>
          
          <td>
              <?php if ($linha['tipo_etapa'] === 'subtitulo') { ?>
                <input type="text" name="etapa[]" value="<?php echo htmlspecialchars($linha['etapa']); ?>" 
                  style="width:100%; min-width:200px; font-family:'Poppins', sans-serif; font-size:13px; padding:4px 8px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box;">
                <?php } else { ?>
                <textarea name="etapa[]" rows="2" class="campo-etapa" 
                  style="width:100%; font-family:'Poppins', sans-serif; font-size:13px; padding:4px 8px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box;"><?php echo htmlspecialchars($linha['etapa']); ?></textarea>
              <?php } ?>
              <input type="hidden" name="ids[]" value="<?php echo $linha['id']; ?>">
              <input type="hidden" name="tipo_etapa[]" value="<?php echo htmlspecialchars($linha['tipo_etapa']); ?>">
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
      <button type="button" onclick="addTitleRow()">Adicionar Etapa</button>
      <button type="button" onclick="addRow()">Adicionar Sub-Etapa</button>
      <button type="button" onclick="deleteRow()">Excluir Linha</button>
      <button type="submit" name="salvar" id="submit" style="background-color:rgb(42, 179, 0);">Salvar</button>
      <button type="button" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
    </div>
  </form>
  
</div>

<script>

document.addEventListener('keydown', function (e) {
  if (e.key === "ArrowDown") {
    const campoAtual = document.activeElement;
    if (!campoAtual.classList.contains('campo-tabela')) return;

    const campos = Array.from(document.querySelectorAll('.campo-tabela'));
    const indexAtual = campos.indexOf(campoAtual);
    const proximoCampo = campos[indexAtual + 7]; // pula para mesma coluna na linha seguinte (7 colunas)

    if (proximoCampo) {
      e.preventDefault();
      proximoCampo.focus();
    }
  }
});

document.querySelector('form').addEventListener('submit', function(event) {
  const form = this;
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const linhas = table.rows;
  let temLinhaValida = false;

  let tituloIndex = -1;
  let datasInicio = [];
  let datasTermino = [];

  for (let i = 0; i < linhas.length; i++) {
    const linha = linhas[i];
    const id = linha.getAttribute('data-id');
    const cells = linha.cells;

    const etapaField = cells[0].querySelector('textarea, input');
    const tipo = etapaField?.placeholder === 'Título' ? 'subtitulo' : 'linha';

    const campos = [
      etapaField?.value.trim() || '',
      cells[1].querySelector('input')?.value.trim() || '',
      cells[2].querySelector('input')?.value.trim() || '',
      cells[3].querySelector('input')?.value.trim() || '',
      cells[4].querySelector('input')?.value.trim() || '',
      cells[5].querySelector('input')?.value.trim() || ''
    ];

    const linhaEstaVazia = campos.every(c => c === '');
    if (linhaEstaVazia) continue;

    temLinhaValida = true;

    if (!id) {
      const inputTipo = document.createElement('input');
      inputTipo.type = 'hidden';
      inputTipo.name = 'tipo_etapa[]';
      inputTipo.value = tipo;
      form.appendChild(inputTipo);

      const inputId = document.createElement('input');
      inputId.type = 'hidden';
      inputId.name = 'ids[]';
      inputId.value = '';
      form.appendChild(inputId);
    }

    // Lógica de preenchimento automático para subtítulos
    if (tipo === 'subtitulo') {
      if (tituloIndex !== -1 && datasInicio.length > 0 && datasTermino.length > 0) {
        preencherDatas(linhas[tituloIndex], datasInicio, datasTermino);
      }
      tituloIndex = i;
      datasInicio = [];
      datasTermino = [];
    } else if (tipo === 'linha') {
      const dtInicio = cells[1].querySelector('input')?.value;
      const dtFim = cells[2].querySelector('input')?.value;
      if (dtInicio) datasInicio.push(dtInicio);
      if (dtFim) datasTermino.push(dtFim);
    }
  }

  if (tituloIndex !== -1 && datasInicio.length > 0 && datasTermino.length > 0) {
    preencherDatas(linhas[tituloIndex], datasInicio, datasTermino);
  }

  function preencherDatas(tituloRow, inicios, fins) {
    const campoInicio = tituloRow.querySelector('input[name="inicio_previsto[]"]');
    const campoFim = tituloRow.querySelector('input[name="termino_previsto[]"]');

    const menorData = inicios.sort()[0];
    const maiorData = fins.sort().reverse()[0];

    if (campoInicio) campoInicio.value = menorData;
    if (campoFim) campoFim.value = maiorData;
  }

  if (!temLinhaValida) {
    event.preventDefault();
    alert('Nenhuma medição válida para salvar!');
  } else {
    const inputs = form.querySelectorAll('textarea, input[type="text"], input[type="number"], input[type="date"]');
    inputs.forEach(input => {
      input.style.backgroundColor = '#e0ffe0';
      setTimeout(() => input.style.backgroundColor = '', 1000);
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const inputsIdCustom = document.querySelectorAll('input[name="id_etapa_custom[]"]');
  let maiorTitulo = 0;

  inputsIdCustom.forEach(input => {
    const valor = input.value.trim();
    if (valor && !valor.includes('.')) {
      const numero = parseInt(valor);
      if (!isNaN(numero) && numero > maiorTitulo) {
        maiorTitulo = numero;
      }
    }
  });

  currentTitleIndex = maiorTitulo;
  subIndex = 1;

  // Atualizar subIndex para evitar repetição em subetapas
  inputsIdCustom.forEach(input => {
    const valor = input.value.trim();
    if (valor.startsWith(maiorTitulo + ".")) {
      const partes = valor.split(".");
      const subNum = parseInt(partes[1]);
      if (!isNaN(subNum) && subNum >= subIndex) {
        subIndex = subNum + 1;
      }
    }
  });
});

function addTitleRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow(); // <-- CRIAR AQUI
  const inputsIdCustom = table.querySelectorAll('input[name="id_etapa_custom[]"]');

  let maiores = Array.from(inputsIdCustom)
    .map(input => input.value.trim())
    .filter(val => val && !val.includes('.'))
    .map(val => parseInt(val))
    .filter(val => !isNaN(val));

  currentTitleIndex = maiores.length > 0 ? Math.max(...maiores) : 0;
  currentTitleIndex++;
  subIndex = 1;

  const campos = ['etapa', 'inicio_previsto', 'termino_previsto', 'inicio_real', 'termino_real', 'evolutivo'];

  const idCell = newRow.insertCell();
  const idInput = document.createElement('input');
  idInput.type = 'text';
  idInput.name = 'id_etapa_custom[]';
  idInput.readOnly = true;
  idInput.value = currentTitleIndex;
  idInput.style = 'width: 100%; font-size: 13px; padding: 4px 8px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;';
  idCell.appendChild(idInput);

  campos.forEach((campo, index) => {
    const cell = newRow.insertCell();
    const input = document.createElement('input');
    input.name = campo + '[]';
    input.type = campo === 'evolutivo' ? 'number' : 'date';
    if (campo === 'etapa') {
      input.type = 'text';
      input.placeholder = 'Título';
    }
    if (campo === 'evolutivo') {
      input.min = 0;
      input.max = 100;
      input.step = 0.1;
      input.placeholder = '0 a 100%';
    }
    input.style = 'width: 100%; font-size: 13px; padding: 4px 8px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;';
    cell.appendChild(input);
  });

  // Tipo oculto
  const tipoCell = newRow.insertCell();
  tipoCell.style.display = 'none';
  const tipoInput = document.createElement('input');
  tipoInput.type = 'hidden';
  tipoInput.name = 'tipo_etapa[]';
  tipoInput.value = 'subtitulo';
  tipoCell.appendChild(tipoInput);
}

function addRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const inputsIdCustom = table.querySelectorAll('input[name="id_etapa_custom[]"]');

  let ultTitulo = null;
  for (let i = inputsIdCustom.length - 1; i >= 0; i--) {
    const val = inputsIdCustom[i].value.trim();
    if (val && !val.includes('.')) {
      ultTitulo = val;
      break;
    }
  }

  if (!ultTitulo) {
    alert("Adicione uma Etapa antes de adicionar Sub-Etapas.");
    return;
  }

  // Verificar quantas subetapas já existem para esse título
  let maiorSub = 0;
  inputsIdCustom.forEach(input => {
    const val = input.value.trim();
    if (val.startsWith(ultTitulo + ".")) {
      const partes = val.split(".");
      const sub = parseInt(partes[1]);
      if (!isNaN(sub) && sub > maiorSub) {
        maiorSub = sub;
      }
    }
  });

  const newSubId = `${ultTitulo}.${maiorSub + 1}`;

  // Inserir nova linha
  const newRow = table.insertRow();
  const campos = ['etapa', 'inicio_previsto', 'termino_previsto', 'inicio_real', 'termino_real', 'evolutivo'];

  // Coluna ID
  const idCell = newRow.insertCell();
  const idInput = document.createElement('input');
  idInput.type = 'text';
  idInput.name = 'id_etapa_custom[]';
  idInput.readOnly = true;
  idInput.value = newSubId;
  idInput.style.width = '100%';
  idInput.style.fontSize = '13px';
  idInput.style.padding = '4px 8px';
  idInput.style.border = '1px solid #ccc';
  idInput.style.borderRadius = '6px';
  idInput.style.boxSizing = 'border-box';
  idCell.appendChild(idInput);

  // Campos principais
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
    } else {
      const input = document.createElement('input');
      input.name = campo + '[]';
      input.type = campo === 'evolutivo' ? 'number' : 'date';
      if (campo === 'evolutivo') {
        input.min = 0;
        input.max = 100;
        input.step = 0.1;
        input.placeholder = '0 a 100%';
      }
      input.style.width = '100%';
      input.style.border = '1px solid #ccc';
      input.style.borderRadius = '6px';
      input.style.height = '20px';
      input.style.padding = '4px 8px';
      input.style.boxSizing = 'border-box';
      cell.appendChild(input);
    }
  });

  // Campo oculto tipo_etapa
  const tipoCell = newRow.insertCell();
  tipoCell.style.display = "none";
  const tipoInput = document.createElement('input');
  tipoInput.type = 'hidden';
  tipoInput.name = 'tipo_etapa[]';
  tipoInput.value = 'linha';
  tipoCell.appendChild(tipoInput);
}


function deleteRow() {
  const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
  const lastRow = table.rows[table.rows.length - 1];


  if (!lastRow) return;

  const id = lastRow.getAttribute('data-id');

  if (id) {
    fetch(`marcos_excluir_linha.php?id=${id}`, { method: 'GET' })
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