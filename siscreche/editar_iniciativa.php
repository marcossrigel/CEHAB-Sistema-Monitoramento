<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include("config.php");

if (!isset($_GET['id'])) {
    echo "ID não fornecido.";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM iniciativas WHERE id = $id";
$resultado = $conexao->query($sql);

if ($resultado->num_rows == 0) {
    header("Location: visualizar.php");
    exit;
}

$row = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ib_status = $_POST['ib_status'];
    $data_vistoria = $_POST['data_vistoria'];
    $ib_execucao = $_POST['ib_execucao'];
    $ib_previsto = $_POST['ib_previsto'];
    $ib_variacao = $_POST['ib_variacao'];
    $ib_valor_medio = $_POST['ib_valor_medio'];
    $objeto = $_POST['objeto'];
    $informacoes_gerais = $_POST['informacoes_gerais'];
    $observacoes = $_POST['observacoes'];

    $update = "UPDATE iniciativas SET 
        ib_status = '$ib_status',
        data_vistoria = '$data_vistoria',
        ib_execucao = '$ib_execucao',
        ib_previsto = '$ib_previsto',
        ib_variacao = '$ib_variacao',
        ib_valor_medio = '$ib_valor_medio',
        objeto = '$objeto',
        informacoes_gerais = '$informacoes_gerais',
        observacoes = '$observacoes'
        WHERE id = $id";

    if ($conexao->query($update)) {
        header("Location: visualizar.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Iniciativa</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e9eef1;
      padding: 20px;
      margin: 0;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h1 {
      font-size: 28px;
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }

    .linha {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 20px;
    }

    .linha-atividade {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 20px;
    }

    .campo {
      flex: 1;
      min-width: 250px;
      display: flex;
      flex-direction: column;
    }

    form input[type="text"],
    form input[type="date"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
      font-size: 14px;
    }

    textarea {
      resize: vertical;
      height: 100px;
      font-family: inherit;
    }

    label {
      font-size: 14px;
      color: #333;
      margin-bottom: 4px;
      font-weight: bold;
    }

    form button,
    .btn-azul {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4da6ff;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    form button:hover,
    .btn-azul:hover {
      background-color: #3399ff;
    }

    .botao-voltar {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }

    #modalConfirmacao {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 9999;
      padding: 10px;
      box-sizing: border-box;
    }

    #modalConfirmacao > div {
      background: white;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      max-width: 400px;
      width: 100%;
    }

    @media (max-width: 600px) {
      .linha {
        flex-direction: column;
      }

      .campo {
        min-width: 100%;
      }

      h1 {
        font-size: 24px;
      }

      .container {
        padding: 15px;
      }

      form button,
      .btn-azul {
        width: 100%;
      }
    }
  </style>
</head>

<body>
<div class="container">
  <h1>Editar Iniciativa</h1>
  <form method="post">
    <div class="linha">
      <div class="campo">
        <label>Iniciativa:</label>
        <input type="text" name="iniciativa" value="<?php echo htmlspecialchars($row['iniciativa']); ?>" readonly>
      </div>
      <div class="campo">
        <label>Status:</label>
        <input type="text" name="ib_status" value="<?php echo htmlspecialchars($row['ib_status']); ?>">
      </div>
      <div class="campo">
        <label>Data da Vistoria:</label>
        <input type="date" name="data_vistoria" value="<?php echo htmlspecialchars($row['data_vistoria']); ?>">
      </div>
    </div>

    <div class="linha">
      <div class="campo">
        <label>Execução:</label>
        <input type="text" name="ib_execucao" value="<?php echo htmlspecialchars($row['ib_execucao']); ?>">
      </div>
      <div class="campo">
        <label>Previsto:</label>
        <input type="text" name="ib_previsto" value="<?php echo htmlspecialchars($row['ib_previsto']); ?>">
      </div>
      <div class="campo">
        <label>Variação:</label>
        <input type="text" name="ib_variacao" value="<?php echo htmlspecialchars($row['ib_variacao']); ?>">
      </div>
    </div>

    <div class="linha">
      <div class="campo">
        <label>Valor Médio:</label>
        <input type="text" name="ib_valor_medio" value="<?php echo htmlspecialchars($row['ib_valor_medio']); ?>">
      </div>
      <div class="campo">
        <label>Secretaria:</label>
        <input type="text" name="ib_secretaria" value="<?php echo htmlspecialchars($row['ib_secretaria']); ?>" readonly>
      </div>
      <div class="campo">
        <label>Órgão:</label>
        <input type="text" name="ib_orgao" value="<?php echo htmlspecialchars($row['ib_orgao']); ?>" readonly>
      </div>
    </div>

    <div class="linha">
      <div class="campo">
        <label>Processo SEI:</label>
        <input type="text" name="ib_numero_processo_sei" value="<?php echo htmlspecialchars($row['ib_numero_processo_sei']); ?>" readonly>
      </div>
      <div class="campo">
        <label>Gestor Responsável:</label>
        <input type="text" name="ib_gestor_responsavel" value="<?php echo htmlspecialchars($row['ib_gestor_responsavel']); ?>" readonly>
      </div>
      <div class="campo">
        <label>Fiscal Responsável:</label>
        <input type="text" name="ib_fiscal" value="<?php echo htmlspecialchars($row['ib_fiscal']); ?>" readonly>
      </div>
    </div>

    <div class="linha-atividade">
      <div class="campo">
        <label>OBJETO</label>
        <textarea name="objeto"><?php echo htmlspecialchars($row['objeto']); ?></textarea>
      </div>
      <div class="campo">
        <label>Informações Gerais</label>
        <textarea name="informacoes_gerais"><?php echo htmlspecialchars($row['informacoes_gerais']); ?></textarea>
      </div>
      <div class="campo">
        <label>OBSERVAÇÕES (PONTOS CRÍTICOS)</label>
        <textarea name="observacoes"><?php echo htmlspecialchars($row['observacoes']); ?></textarea>
      </div>
    </div>

    <button type="submit">Salvar Alterações</button>
    <button type="button" onclick="abrirModal()" style="background-color: transparent; border: none; cursor: pointer; font-size: 18px; color: red; font-weight: bold;">delete</button>
  </form>

  <div class="botao-voltar">
    <button class="btn-azul" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
  </div>
</div>

<div id="modalConfirmacao">
  <div>
    <p style="margin-bottom: 20px;">Tem certeza que deseja excluir esta iniciativa?</p>
    <button onclick="confirmarExclusao()">Sim</button>
    <button onclick="fecharModal()" style="background-color: #4da6ff; color: white;">Cancelar</button>
  </div>
</div>

<script>
  function abrirModal() {
    document.getElementById('modalConfirmacao').style.display = 'flex';
  }
  function fecharModal() {
    document.getElementById('modalConfirmacao').style.display = 'none';
  }
  function confirmarExclusao() {
    window.location.href = 'excluir_iniciativa.php?id=<?php echo $row["id"]; ?>';
  }
</script>
</body>
</html>