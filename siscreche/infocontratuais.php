<?php
session_start();
include_once('config.php');

$id_iniciativa = (int) ($_GET['id_iniciativa'] ?? 0);
$nome_iniciativa = '';

$processo = '';
$empresa = '';
$data_ass = '';
$data_os = '';
$prazo = '';
$adit_prazo = '';
$adit_susp = '';
$valor_inicial = 0;
$adit_adicao = '';
$adit_supressao = '';
$valor_total = 0;
$secretaria = '';

if ($id_iniciativa > 0) {
    $stmt = $conexao->prepare("SELECT iniciativa FROM iniciativas WHERE id = ?");
    $stmt->bind_param("i", $id_iniciativa);
    $stmt->execute();
    $stmt->bind_result($nome_iniciativa);
    $stmt->fetch();
    $stmt->close();
}

if ($id_iniciativa > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $consulta = $conexao->prepare("SELECT processo_licitatorio, empresa, data_assinatura_contrato, data_os, prazo_execucao_original, aditivo_prazo, aditivo_suspensao, valor_inicial_contrato, aditivo_supressao, valor_contrato, secretaria_demandante FROM contratuais WHERE id_iniciativa = ? ORDER BY id DESC LIMIT 1");
    $consulta->bind_param("i", $id_iniciativa);
    $consulta->execute();
    $consulta->bind_result(
        $processo, $empresa, $data_ass, $data_os, $prazo,
        $adit_prazo, $adit_susp, $valor_inicial,
        $adit_supressao, $valor_total, $secretaria
    );
    $consulta->fetch();
    $consulta->close();
}

function tratarValor($valor) {
    return floatval(str_replace(['R$', '.', ','], ['', '', '.'], trim($valor)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $processo = $_POST['processo_licitatorio'] ?? '';
    $empresa = $_POST['empresa'] ?? '';
    $data_ass = $_POST['data_assinatura_contrato'] ?? null;
    $data_os = $_POST['data_os'] ?? null;
    $prazo = $_POST['prazo_execucao_original'] ?? '';
    $adit_prazo = $_POST['aditivo_prazo'] ?? '';
    $adit_susp = $_POST['aditivo_suspensao'] ?? '';
    $adit_adicao = tratarValor($_POST['aditivo_adicao'] ?? '0');
    $adit_supressao = tratarValor($_POST['aditivo_supressao'] ?? '0');
    $valor_total = tratarValor($_POST['valor_contrato'] ?? '0');
    $secretaria = $_POST['secretaria_demandante'] ?? '';

    $valor_homologado_atual = 0;
    $busca = $conexao->prepare("SELECT valor_inicial_contrato FROM contratuais WHERE id_iniciativa = ? ORDER BY id DESC LIMIT 1");
    $busca->bind_param("i", $id_iniciativa);
    $busca->execute();
    $busca->bind_result($valor_homologado_atual);
    $busca->fetch();
    $busca->close();

    $valor_inicial = $valor_homologado_atual + $adit_adicao;


    $adit_adicao = '';
    $adit_supressao = '';

    $sql = "INSERT INTO contratuais (
        id_iniciativa, processo_licitatorio, empresa, data_assinatura_contrato, data_os,
        prazo_execucao_original, aditivo_prazo, aditivo_suspensao,
        valor_inicial_contrato, aditivo_adicao, aditivo_supressao,
        valor_contrato, secretaria_demandante
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("issssssddddds",
        $id_iniciativa, $processo, $empresa, $data_ass, $data_os,
        $prazo, $adit_prazo, $adit_susp,
        $valor_inicial, $adit_adicao, $adit_supressao, $valor_total, $secretaria
    );

    if ($stmt->execute()) {
        echo "<script>alert('Informações salvas com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao salvar: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informações Contratuais</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
  <style>
    :root {
      --color-white: #ffffff;
      --color-gray: #e3e8ec;
      --color-dark: #1d2129;
      --color-blue: #4da6ff;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--color-gray);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 30px 10px;
    }
    .container {
      background: var(--color-white);
      padding: 30px 20px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 900px;
    }
    .main-title {
      font-size: 24px;
      font-weight: 500;
      color: #2c2c2c;
      text-align: center;
      margin-bottom: 30px;
      padding: 0 10px;
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 10px;
    }
    th, td {
      text-align: left;
      padding: 8px;
    }
    .hide-mobile {
      display: table-cell;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    .button-group {
      margin-top: 30px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }
    button {
      padding: 12px 20px;
      background-color: var(--color-blue);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #3399ff;
    }
    @media (max-width: 600px) {
      .main-title {
        font-size: 20px;
      }
      input {
        font-size: 14px;
      }
      table th.hide-mobile {
        display: none;
      }
      table td {
        display: block;
        width: 100%;
      }
      table tr {
        display: block;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <form method="post" action="">
      <div class="main-title">
        <?= 'Informações Contratuais' . ($nome_iniciativa ? ' - ' . htmlspecialchars($nome_iniciativa) : '') ?>
      </div>

      <table>
        <tr><th class="hide-mobile">Campo</th><th class="hide-mobile">Valor</th></tr>
        <tr><td>Processo Licitatório</td><td><input type="text" name="processo_licitatorio" value="<?= htmlspecialchars($processo) ?>"></td></tr>
        <tr><td>Empresa</td><td><input type="text" name="empresa" value="<?= htmlspecialchars($empresa) ?>"></td></tr>
        <tr><td>Data Assinatura do Contrato</td><td><input type="date" name="data_assinatura_contrato" value="<?= $data_ass ?>" required></td></tr>
        <tr><td>Data da O.S.</td><td><input type="date" name="data_os" value="<?= $data_os ?>" required></td></tr>
        <tr><td>Prazo de Execução do Contrato</td><td><input type="text" name="prazo_execucao_original" value="<?= htmlspecialchars($prazo) ?>"></td></tr>
        <tr><td>Aditivo de Prazo +</td><td><input type="text" name="aditivo_prazo" value="<?= htmlspecialchars($adit_prazo) ?>"></td></tr>
        <tr><td>Aditivo de Suspensão +</td><td><input type="text" name="aditivo_suspensao" value="<?= htmlspecialchars($adit_susp) ?>"></td></tr>
        <tr><td>Valor Homologado do Contrato</td><td><input type="text" name="valor_inicial_contrato" value="<?= number_format($valor_inicial, 2, ',', '.') ?>" readonly></td></tr>
        <tr><td>Valor de Aditivo de Adição do Contrato</td><td><input type="text" name="aditivo_adicao" value=""></td></tr>
        <tr><td>Valor de Aditivo de Supressão do Contrato</td><td><input type="text" name="aditivo_supressao" value=""></td></tr>
        <tr><td>Valor Total do Contrato (SOMA)</td><td><input type="text" name="valor_contrato" value="<?= number_format($valor_total, 2, ',', '.') ?>"></td></tr>
        <tr><td>Secretaria Demandante</td><td><input type="text" name="secretaria_demandante" value="<?= htmlspecialchars($secretaria) ?>"></td></tr>
      </table>

      <div class="button-group">
        <button type="submit">Salvar</button>
        <button type="button" onclick="window.location.href='visualizar.php'">Voltar</button>
      </div>
    </form>
  </div>
</body>
</html>
