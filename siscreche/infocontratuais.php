<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include_once('config.php');

$id_iniciativa = isset($_POST['id_iniciativa']) ? intval($_POST['id_iniciativa']) : (isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0);

$query_nome = "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa";
$result_nome = mysqli_query($conexao, $query_nome);
$linha_nome = mysqli_fetch_assoc($result_nome);
$nome_iniciativa = $linha_nome['iniciativa'] ?? 'Iniciativa Desconhecida';

$query_busca = "SELECT * FROM contratuais WHERE id_usuario = {$_SESSION['id_usuario']} AND id_iniciativa = $id_iniciativa";
$resultado = mysqli_query($conexao, $query_busca);
$dados = mysqli_fetch_assoc($resultado);

if (isset($_POST['salvar'])) {
    $processo_licitatorio = mysqli_real_escape_string($conexao, $_POST['processo_licitatorio']);
    $empresa = mysqli_real_escape_string($conexao, $_POST['empresa']);
    $data_assinatura_contrato = $_POST['data_assinatura_contrato'];
    $dara_os = $_POST['dara_os'];
    $prazo_execucao_original = $_POST['prazo_execucao_original'];
    $prazo_execucao_atual = $_POST['prazo_execucao_atual'];

    $valor_inicial_obra = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor_inicial_obra']);
    $valor_aditivo_obra = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor_aditivo_obra']);  // <== AQUI
    $valor_total_obra = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor_total_obra']);
    $valor_inicial_contrato = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor_inicial_contrato']);
    $valor_aditivo = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor_aditivo']);
    $valor_contrato = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor_contrato']);

    $cod_subtracao = mysqli_real_escape_string($conexao, $_POST['cod_subtracao']); // <== AQUI
    $secretaria_demandante = mysqli_real_escape_string($conexao, $_POST['secretaria_demandante']);

    if ($dados) {
        $query_update = "UPDATE contratuais SET 
            processo_licitatorio='$processo_licitatorio',
            empresa='$empresa',
            data_assinatura_contrato='$data_assinatura_contrato',
            dara_os='$dara_os',
            prazo_execucao_original='$prazo_execucao_original',
            prazo_execucao_atual='$prazo_execucao_atual',
            valor_inicial_obra='$valor_inicial_obra',
            valor_aditivo_obra='$valor_aditivo_obra',
            valor_total_obra='$valor_total_obra',
            valor_inicial_contrato='$valor_inicial_contrato',
            valor_aditivo='$valor_aditivo',
            valor_contrato='$valor_contrato',
            cod_subtracao='$cod_subtracao',
            secretaria_demandante='$secretaria_demandante'
            WHERE id_usuario={$_SESSION['id_usuario']} AND id_iniciativa=$id_iniciativa";
        mysqli_query($conexao, $query_update);
    } else {
        $query_insert = "INSERT INTO contratuais (id_usuario, id_iniciativa, processo_licitatorio, empresa, data_assinatura_contrato, dara_os, prazo_execucao_original, prazo_execucao_atual, valor_inicial_obra, valor_aditivo_obra, valor_total_obra, valor_inicial_contrato, valor_aditivo, valor_contrato, cod_subtracao, secretaria_demandante) VALUES 
        ({$_SESSION['id_usuario']}, $id_iniciativa, '$processo_licitatorio', '$empresa', '$data_assinatura_contrato', '$dara_os', '$prazo_execucao_original', '$prazo_execucao_atual', '$valor_inicial_obra', '$valor_aditivo_obra', '$valor_total_obra', '$valor_inicial_contrato', '$valor_aditivo', '$valor_contrato', '$cod_subtracao', '$secretaria_demandante')";
        mysqli_query($conexao, $query_insert);
    }

    header("Location: infocontratuais.php?id_iniciativa=$id_iniciativa");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informações Contratuais</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
      justify-content: flex-start;
      align-items: center;
      padding: 30px 0;
    }

    .container {
      background: var(--color-white);
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 900px;
    }

    .main-title {
      font-size: 28px;
      font-weight: 600;
      color: var(--color-dark);
      text-align: center;
      margin-bottom: 30px;
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
  </style>
</head>

<body>
  <div class="container">
    
    <form method="post" action="infocontratuais.php">
      <input type="hidden" name="id_iniciativa" value="<?php echo $id_iniciativa; ?>">

      <div class="main-title"><?php echo htmlspecialchars($nome_iniciativa); ?> - Informações Contratuais</div>

      <table>
        <tr><th>Campo</th><th>Valor</th></tr>
        <tr><td>Processo Licitatório</td><td><input type="text" name="processo_licitatorio" value="<?php echo $dados['processo_licitatorio'] ?? ''; ?>"></td></tr>
        <tr><td>Empresa</td><td><input type="text" name="empresa" value="<?php echo $dados['empresa'] ?? ''; ?>"></td></tr>
        
        <tr><td>Data Assinatura do Contrato</td><td><input type="date" name="data_assinatura_contrato" value="<?php echo $dados['data_assinatura_contrato'] ?? ''; ?>"></td></tr>
        <tr><td>Data da O.S.</td><td><input type="date"  name="dara_os" value="<?php echo $dados['dara_os'] ?? ''; ?>"></td></tr>
        <tr><td>Prazo de Execução Original</td><td><input type="text" name="prazo_execucao_original" value="<?php echo $dados['prazo_execucao_original'] ?? ''; ?>"></td></tr>
        <tr><td>Prazo de Execução Atual</td><td><input type="text" name="prazo_execucao_atual" value="<?php echo $dados['prazo_execucao_atual'] ?? ''; ?>"></td></tr>
        
        <tr><td>Valor Inicial da Obra</td><td><input type="text" name="valor_inicial_obra" value="<?php echo $dados['valor_inicial_obra'] ?? ''; ?>"></td></tr>
        <tr><td>Valor de Aditivo da Obra</td><td><input type="text" name="valor_aditivo_obra" value="<?php echo $dados['valor_aditivo_obra'] ?? ''; ?>"></td></tr>
        <tr><td>Valor Total da Obra</td><td><input type="text" name="valor_total_obra" value="<?php echo $dados['valor_total_obra'] ?? ''; ?>"></td></tr>
        <tr><td>Valor Inicial do Contrato</td><td><input type="text" name="valor_inicial_contrato" value="<?php echo $dados['valor_inicial_contrato'] ?? ''; ?>"></td></tr>
        <tr><td>Valor do Aditivo</td><td><input type="text" name="valor_aditivo" value="<?php echo $dados['valor_aditivo'] ?? ''; ?>"></td></tr>
        <tr><td>Valor Total do Contrato</td><td><input type="text" name="valor_contrato" value="<?php echo $dados['valor_contrato'] ?? ''; ?>"></td></tr>
        
        <tr><td>CÓD. DA SUBTRAÇÃO (LOA)</td><td><input type="text" name="cod_subtracao" readonly value="<?php echo $dados['cod_subtracao'] ?? ''; ?>"></td></tr>
        
        <tr><td>Secretaria Demandante</td><td><input type="text" name="secretaria_demandante" value="<?php echo $dados['secretaria_demandante'] ?? ''; ?>"></td></tr>
      </table>

      <div class="button-group">
        <button type="submit" name="salvar" style="background-color:rgb(42, 179, 0);">Salvar</button>
        <button type="button" onclick="window.location.href='visualizar.php';">< Voltar</button>
      </div>

    </form>
  </div>

<script>
function calcularSubtracao() {
    const valorTotalObra = parseFloat(document.querySelector('input[name="valor_total_obra"]').value.replace(',', '.')) || 0;
    const valorInicialContrato = parseFloat(document.querySelector('input[name="valor_inicial_contrato"]').value.replace(',', '.')) || 0;

    const resultado = valorTotalObra - valorInicialContrato;

    document.querySelector('input[name="cod_subtracao"]').value = resultado.toFixed(2);
}

document.querySelector('input[name="valor_inicial_obra"]').addEventListener('input', calcularSubtracao);
document.querySelector('input[name="valor_inicial_contrato"]').addEventListener('input', calcularSubtracao);

window.addEventListener('load', calcularSubtracao);
</script>

</body>
</html>
