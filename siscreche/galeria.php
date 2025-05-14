<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

include_once("config.php");

$id_usuario = $_SESSION['id_usuario'];
$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

$query = "SELECT * FROM fotos WHERE id_iniciativa = $id_iniciativa AND id_usuario = $id_usuario ORDER BY id ASC";
$result = mysqli_query($conexao, $query);

$nome_iniciativa = "Desconhecida";
$res = mysqli_query($conexao, "SELECT iniciativa FROM iniciativas WHERE id = $id_iniciativa");
if ($linha = mysqli_fetch_assoc($res)) {
    $nome_iniciativa = $linha['iniciativa'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Galeria de Fotos</title>
  <style>
body {
  font-family: Arial, sans-serif;
  background-color: #f1f1f1;
  margin: 0;
  padding: 30px;
}

h2 {
  text-align: center;
  margin-bottom: 30px;
}

.galeria {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.foto {
  border: 1px solid #ccc;
  padding: 10px;
  width: 260px;
  text-align: center;
  border-radius: 8px;
  background: #fff;
}

.foto img {
  width: 100%;
  border-radius: 6px;
}

.foto textarea {
  width: 100%;
  resize: none;
  height: 60px;
  margin-top: 5px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.voltar-container {
  display: flex;
  justify-content: center;
  margin-top: 40px;
}

.btn-voltar {
  background-color: #4da6ff;
  color: white;
  padding: 10px 20px;
  font-size: 14px;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-voltar:hover {
  background-color: #3399ff;
}
</style>
</head>

<body>
  <h2>Galeria - Iniciativa <?php echo htmlspecialchars($nome_iniciativa); ?></h2>

  <div class="galeria">
    <?php while ($foto = mysqli_fetch_assoc($result)): ?>
      <div class="foto">
        <img src="uploads/<?php echo htmlspecialchars($foto['caminho']); ?>" alt="">
        <textarea readonly><?php echo htmlspecialchars($foto['descricao']); ?></textarea>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="voltar-container">
    <button type="button" class="btn-voltar" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
  </div>
</body>
</html>
