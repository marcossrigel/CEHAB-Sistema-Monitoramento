<?php
include("config.php");

if (!isset($_GET['id'])) {
    echo "ID não fornecido.";
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM iniciativas WHERE id = $id";
$resultado = $conexao->query($sql);

if ($resultado->num_rows == 0) {
    echo "Iniciativa não encontrada.";
    exit;
}

$row = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iniciativa = $_POST['iniciativa'];
    $ib_status = $_POST['ib_status'];
    $data_vistoria = $_POST['data_vistoria'];
    $ib_execucao = $_POST['ib_execucao'];
    $ib_previsto = $_POST['ib_previsto'];
    $ib_variacao = $_POST['ib_variacao'];
    $ib_valor_medio = $_POST['ib_valor_medio'];
    $ib_secretaria = $_POST['ib_secretaria'];
    $ib_orgao = $_POST['ib_orgao'];

    $update = "UPDATE iniciativas SET 
        iniciativa = '$iniciativa',
        ib_status = '$ib_status',
        data_vistoria = '$data_vistoria',
        ib_execucao = '$ib_execucao',
        ib_previsto = '$ib_previsto',
        ib_variacao = '$ib_variacao',
        ib_valor_medio = '$ib_valor_medio',
        ib_secretaria = '$ib_secretaria',
        ib_orgao = '$ib_orgao'
        WHERE id = $id";

    if ($conexao->query($update)) {
        echo "<script>alert('Atualizado com sucesso!'); window.location.href='visualizar_iniciativas.php';</script>";
    } else {
        echo "Erro ao atualizar: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Iniciativa</title>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #e9eef1;
  padding: 40px;
}

.container {
  max-width: 800px;
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

form label {
  font-weight: bold;
  margin-top: 10px;
  display: block;
}

form input[type="text"],
form input[type="date"] {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-sizing: border-box;
}

form button {
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

form button:hover {
  background-color: #3399ff;
}

.botao-voltar {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.btn-azul {
  padding: 10px 20px;
  background-color: #4da6ff;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.btn-azul:hover {
  background-color: #3399ff;
}
</style>
</head>
<body>

<div class="container">
    <h1>Editar Iniciativa</h1>
    <form method="post">
        <label>Iniciativa:</label>
        <input type="text" name="iniciativa" value="<?php echo htmlspecialchars($row['iniciativa']); ?>">

        <label>Status:</label>
        <input type="text" name="ib_status" value="<?php echo htmlspecialchars($row['ib_status']); ?>">

        <label>Data da Vistoria:</label>
        <input type="date" name="data_vistoria" value="<?php echo htmlspecialchars($row['data_vistoria']); ?>">

        <label>Execução:</label>
        <input type="text" name="ib_execucao" value="<?php echo htmlspecialchars($row['ib_execucao']); ?>">

        <label>Previsto:</label>
        <input type="text" name="ib_previsto" value="<?php echo htmlspecialchars($row['ib_previsto']); ?>">

        <label>Variação:</label>
        <input type="text" name="ib_variacao" value="<?php echo htmlspecialchars($row['ib_variacao']); ?>">

        <label>Valor Médio:</label>
        <input type="text" name="ib_valor_medio" value="<?php echo htmlspecialchars($row['ib_valor_medio']); ?>">

        <label>Secretaria:</label>
        <input type="text" name="ib_secretaria" value="<?php echo htmlspecialchars($row['ib_secretaria']); ?>">

        <label>Órgão:</label>
        <input type="text" name="ib_orgao" value="<?php echo htmlspecialchars($row['ib_orgao']); ?>">

        <button type="submit">Salvar Alterações</button>
    </form>

    <div class="botao-voltar">
        <button class="btn-azul" onclick="window.location.href='visualizar_iniciativas.php';">&lt; Voltar</button>
    </div>
</div>

</body>
</html>
