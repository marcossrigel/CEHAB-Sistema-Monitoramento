<?php
session_start();
include_once("config.php");

$id_iniciativa = $_GET['id_iniciativa'] ?? null;
$nome_iniciativa = '';

if ($id_iniciativa) {
    $stmt = $conexao->prepare("SELECT iniciativa FROM iniciativas WHERE id = ?");
    $stmt->bind_param("i", $id_iniciativa);
    $stmt->execute();
    $stmt->bind_result($nome_iniciativa);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_usuario'])) {
        die("Usuário não autenticado.");
    }

    $id_usuario = $_SESSION['id_usuario'];
    $id_iniciativa = $_POST['id_iniciativa'] ?? null;

    if (!$id_iniciativa || !isset($_POST['valor_orcamento'])) {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        exit();
    }

    foreach ($_POST['valor_orcamento'] as $index => $valor_orcamento) {
        $valor_bm = $_POST['valor_bm'][$index];
        $saldo_obra = $_POST['saldo_obra'][$index];
        $bm = $_POST['bm'][$index];
        $data_inicio = $_POST['data_inicio'][$index];
        $data_fim = $_POST['data_fim'][$index];
        $data_registro = date('Y-m-d H:i:s'); // data atual da máquina

        $sql = "INSERT INTO medicoes 
                (id_usuario, id_iniciativa, valor_orcamento, valor_bm, saldo_obra, bm, data_inicio, data_fim, data_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iiddssss", 
            $id_usuario,
            $id_iniciativa,
            $valor_orcamento,
            $valor_bm,
            $saldo_obra,
            $bm,
            $data_inicio,
            $data_fim,
            $data_registro
        );
        $stmt->execute();
    }

    echo "<script>window.location.href='visualizar.php?id_iniciativa=$id_iniciativa';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Acompanhamento de Medições</title>
  <!-- Importar fonte Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #e4eaef;
    margin: 0;
    padding: 20px;
  }

  .container {
    max-width: 1200px;
    background: #fff;
    border-radius: 15px;
    margin: auto;
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
    font-weight: 400; /* ou use: normal */
  }


  .linha {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    flex-wrap: wrap;
  }

  .linha input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    flex: 1;
    min-width: 140px;
    font-family: 'Poppins', sans-serif;
  }

  .linha input:focus {
    outline: none;
    border: 1px solid #4da6ff;
    background-color: #f0f8ff; /* tom azul claro */
  }

  .col {
    flex: 1;
    min-width: 140px;
    max-width: 180px;
  }

  label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
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

  #submit {
    background-color: #28a745;
  }

  button {
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
    font-family: 'Poppins', sans-serif;
  }

  </style>
</head>
<body>

  <div class="container">
    <form id="medicoesForm" method="POST">
      <h2><?php echo $nome_iniciativa ? "$nome_iniciativa - Acompanhamento de Medições" : "Acompanhamento de Medições"; ?></h2>

      <div id="linhasContainer">
        <input type="hidden" name="id_iniciativa" value="<?php echo $_GET['id_iniciativa']; ?>">

        <div class="linha">
          <div class="col"><label>Valor Total da Obra</label></div>
          <div class="col"><label>Valor BM</label></div>
          <div class="col"><label>Saldo da Obra</label></div>
          <div class="col"><label>BM</label></div>
          <div class="col"><label>Data Início</label></div>
          <div class="col"><label>Data Fim</label></div>
        </div>

        <div class="linha">
          <div class="col"><input type="text" name="valor_orcamento[]"></div>
          <div class="col"><input type="text" name="valor_bm[]"></div>
          <div class="col"><input type="text" name="saldo_obra[]"></div>
          <div class="col"><input type="text" name="bm[]"></div>
          <div class="col"><input type="date" name="data_inicio[]"></div>
          <div class="col"><input type="date" name="data_fim[]"></div>
        </div>

        
      </div>

      <div class="button-group">
        <button type="button" onclick="adicionarLinha()">Adicionar Linha</button>
        <button type="button" onclick="excluirLinha()">Excluir Linha</button>
        <button type="submit" name="salvar" id="submit">Salvar</button>
        <button type="button" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
      </div>

    </form>
  </div>

  <script>
    function adicionarLinha() {
      const container = document.getElementById('linhasContainer');
      const linhas = container.querySelectorAll('.linha');
      const ultimaLinha = linhas[linhas.length - 1];
      const novaLinha = ultimaLinha.cloneNode(true);
      novaLinha.querySelectorAll('input').forEach(input => input.value = '');
      container.appendChild(novaLinha);
    }

    function excluirLinha() {
      const container = document.getElementById('linhasContainer');
      const linhas = container.querySelectorAll('.linha');
      if (linhas.length > 1) {
        container.removeChild(linhas[linhas.length - 1]);
      }
    }
  </script>

</body>
</html>
