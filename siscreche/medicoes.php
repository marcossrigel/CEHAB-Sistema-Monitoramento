<?php
session_start();
include_once("config.php");

$id_usuario = $_SESSION['id_usuario'];
$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;
$nome_iniciativa = '';

if ($id_iniciativa > 0) {
    $query = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
    $resultado = mysqli_query($conexao, $query);
    if ($linha = mysqli_fetch_assoc($resultado)) {
        $nome_iniciativa = $linha['iniciativa'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_iniciativa = isset($_POST['id_iniciativa']) ? intval($_POST['id_iniciativa']) : 0;

    foreach ($_POST['valor_orcamento'] as $index => $orcamento) {
        $valor_orcamento = mysqli_real_escape_string($conexao, $_POST['valor_orcamento'][$index]);
        $valor_bm = mysqli_real_escape_string($conexao, $_POST['valor_bm'][$index]);
        $saldo_obra = mysqli_real_escape_string($conexao, $_POST['saldo_obra'][$index]);
        $bm = intval($_POST['bm'][$index]);
        $data_inicio = $_POST['data_inicio'][$index];
        $data_fim = $_POST['data_fim'][$index];
        $data_vistoria = $_POST['data_vistoria'][$index];
        $data_registro = $_POST['data'][$index];

        $sql = "INSERT INTO medicoes (
                    id_usuario, id_iniciativa, valor_orcamento, valor_bm, saldo_obra,
                    bm, data_inicio, data_fim, data_vistoria, data_registro
                ) VALUES (
                    '$id_usuario', '$id_iniciativa', '$valor_orcamento', '$valor_bm', '$saldo_obra',
                    '$bm', '$data_inicio', '$data_fim', '$data_vistoria', '$data_registro'
                )";

        mysqli_query($conexao, $sql);
    }

    header("Location: medicoes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Acompanhamento de Medições</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #e9eef1;
      margin: 0;
      padding: 20px;
    }
    .formulario-box {
      background: #fff;
      padding: 25px 20px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      max-width: 1200px;
      margin: 0 auto;
    }
    h1 {
      font-weight: 500;
      font-size: 22px;
      text-align: center;
      margin-bottom: 25px;
      color: #222;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      overflow-x: auto;
      display: block;
    }
    thead {
      background-color: #eeeeee;
    }
    th, td {
      text-align: center;
      padding: 8px;
      font-size: 14px;
      border: 1px solid #ccc;
      min-width: 120px;
    }
    input[type="text"],
    input[type="date"],
    input[type="number"] {
      width: 100%;
      padding: 7px 10px;
      font-size: 14px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }
    .button-group {
      margin-top: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }
    .button-group button {
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      font-weight: 500;
      cursor: pointer;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: 0.2s;
    }
    .btn-azul {
      background-color: #339af0;
      color: white;
    }
    .btn-azul:hover {
      background-color: #228be6;
    }
    .btn-verde {
      background-color: #2ab300;
      color: white;
    }
    .btn-verde:hover {
      background-color: #219200;
    }
    @media (max-width: 768px) {
      .formulario-box {
        padding: 20px 10px;
      }
      th, td {
        font-size: 12px;
        padding: 6px;
      }
      h1 {
        font-size: 18px;
      }
    }
  </style>
</head>
<body>
  <div class="formulario-box">
    <h1><?php echo $nome_iniciativa ? $nome_iniciativa . ' - ' : ''; ?>Acompanhamento de Medições</h1>
    <form method="post">
      <input type="hidden" name="id_iniciativa" value="<?php echo $id_iniciativa; ?>">
      <table id="tabelaBM">
        <thead>
          <tr>
            <th>Valor Total da Obra</th>
            <th>Valor BM</th>
            <th>Saldo da Obra</th>
            <th>BM</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Data Vistoria</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" name="valor_orcamento[]"></td>
            <td><input type="text" name="valor_bm[]"></td>
            <td><input type="text" name="saldo_obra[]" readonly></td>
            <td><input type="number" name="bm[]"></td>
            <td><input type="date" name="data_inicio[]"></td>
            <td><input type="date" name="data_fim[]"></td>
            <td><input type="date" name="data_vistoria[]"></td>
            <input type="hidden" name="data[]" value="<?php echo date('d-m-Y'); ?>">
          </tr>
        </tbody>
      </table>
      <div class="button-group">
        <button type="button" class="btn-azul" onclick="adicionarLinha()">Adicionar Linha</button>
        <button type="button" class="btn-azul" onclick="excluirLinha()">Excluir Linha</button>
        <button type="submit" class="btn-verde">Salvar</button>
        <button type="button" class="btn-azul" onclick="window.location.href='visualizar.php'">&lt; Voltar</button>
      </div>
    </form>
  </div>
  <script>
    function adicionarLinha() {
      const tabela = document.getElementById('tabelaBM').getElementsByTagName('tbody')[0];
      const novaLinha = tabela.insertRow();
      const dataAtual = new Date().toISOString().split('T')[0];
      novaLinha.innerHTML = `
        <td><input type="text" name="valor_orcamento[]"></td>
        <td><input type="text" name="valor_bm[]"></td>
        <td><input type="text" name="saldo_obra[]" readonly></td>
        <td><input type="number" name="bm[]"></td>
        <td><input type="date" name="data_inicio[]"></td>
        <td><input type="date" name="data_fim[]"></td>
        <td><input type="date" name="data_vistoria[]"></td>
        <input type="hidden" name="data[]" value="${dataAtual}">
      `;
      aplicarEventosLinha(novaLinha);
    }
    function excluirLinha() {
      const tabela = document.getElementById('tabelaBM').getElementsByTagName('tbody')[0];
      if (tabela.rows.length > 1) {
        tabela.deleteRow(tabela.rows.length - 1);
      }
    }
    function parseMoeda(valor) {
      return parseFloat(valor.replace(/\./g, '').replace(',', '.').replace(/[^\d.-]/g, '')) || 0;
    }
    function formatarMoeda(valor) {
      return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }
    function atualizarSaldos() {
      const tabela = document.getElementById('tabelaBM').getElementsByTagName('tbody')[0];
      let saldoAnterior = 0;
      for (let i = 0; i < tabela.rows.length; i++) {
        const row = tabela.rows[i];
        const valorOrcamento = row.querySelector('input[name="valor_orcamento[]"]');
        const valorBM = row.querySelector('input[name="valor_bm[]"]');
        const saldoObra = row.querySelector('input[name="saldo_obra[]"]');
        const orcamento = parseMoeda(valorOrcamento.value);
        const bm = parseMoeda(valorBM.value);
        let novoSaldo = 0;
        if (i === 0) {
          novoSaldo = orcamento - bm;
          saldoAnterior = novoSaldo;
        } else {
          novoSaldo = saldoAnterior - bm;
          saldoAnterior = novoSaldo;
        }
        if (saldoObra) {
          saldoObra.value = formatarMoeda(novoSaldo);
        }
      }
    }
    function aplicarEventosLinha(linha) {
      const orcamentoInput = linha.querySelector('input[name="valor_orcamento[]"]');
      const valorBMInput = linha.querySelector('input[name="valor_bm[]"]');
      [orcamentoInput, valorBMInput].forEach(input => {
        input.addEventListener('input', atualizarSaldos);
        aplicarMascaraMoeda(input);
      });
    }
    window.onload = function () {
      const tabela = document.getElementById('tabelaBM').getElementsByTagName('tbody')[0];
      if (tabela.rows.length > 0) {
        aplicarEventosLinha(tabela.rows[0]);
        const hiddenData = tabela.rows[0].querySelector('input[name="data[]"]');
        if (hiddenData) {
          hiddenData.value = new Date().toISOString().split('T')[0];
        }
      }
    }
    function aplicarMascaraMoeda(input) {
      input.addEventListener('input', function () {
        let valor = input.value.replace(/\D/g, '');
        valor = (parseInt(valor) / 100).toFixed(2) + '';
        valor = valor.replace(".", ",");
        valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        input.value = valor;
        atualizarSaldos();
      });
    }
  </script>
</body>
</html>
