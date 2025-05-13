<?php
include_once("config.php");
$id = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;
$query = "SELECT * FROM fotos WHERE id_iniciativa = $id ORDER BY id ASC";
$result = mysqli_query($conexao, $query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Galeria de Fotos</title>
  <style>
    .galeria { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
    .foto { border: 1px solid #ccc; padding: 10px; width: 260px; text-align: center; border-radius: 8px; background: #fff; }
    .foto img { width: 100%; border-radius: 6px; }
    .foto textarea { width: 100%; resize: none; height: 60px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Galeria - Iniciativa <?php echo $id; ?></h2>
  <div class="galeria">
    <?php while ($foto = mysqli_fetch_assoc($result)): ?>
      <div class="foto">
        <img src="uploads/<?php echo htmlspecialchars($foto['caminho']); ?>" alt="">
        <textarea readonly><?php echo htmlspecialchars($foto['descricao']); ?></textarea>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>