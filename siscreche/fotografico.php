<?php
session_start();
include_once("config.php");

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

// Carrega fotos
$query_fotos = "SELECT * FROM fotos WHERE id_iniciativa = $id_iniciativa ORDER BY id ASC";
$fotos_resultado = mysqli_query($conexao, $query_fotos);
$fotos = [];
while ($linha = mysqli_fetch_assoc($fotos_resultado)) {
    $fotos[] = $linha;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Acompanhamento Fotográfico</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e9eef1;
      padding: 40px;
    }
    .titulo {
      text-align: center;
      font-size: 28px;
      margin-bottom: 30px;
    }
    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 20px;
    }
    .foto-box {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 23%;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px;
    }
    .foto-box img {
      max-width: 100%;
      border-radius: 8px;
    }
    .descricao {
      text-align: center;
      margin-top: 8px;
      font-weight: bold;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="titulo"><?php echo htmlspecialchars($nome_iniciativa ?? "Iniciativa"); ?> - Acompanhamento Fotográfico</div>

  <div class="grid">
    <?php foreach ($fotos as $index => $foto): ?>
      <div class="foto-box">
        <img src="uploads/<?php echo htmlspecialchars($foto['caminho']); ?>" alt="Foto <?php echo $index + 1; ?>">
        <div class="descricao">FOTO <?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>: <?php echo htmlspecialchars($foto['descricao']); ?></div>
      </div>
    <?php endforeach; ?>
  </div>

</body>
</html>
