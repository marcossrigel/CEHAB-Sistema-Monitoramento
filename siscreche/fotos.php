<?php
include_once("config.php");

$query = "
  SELECT f.id, f.caminho, f.descricao, u.nome AS usuario, i.iniciativa
  FROM fotos f
  JOIN usuarios u ON f.id_usuario = u.id_usuario
  JOIN iniciativas i ON f.id_iniciativa = i.id
  ORDER BY u.nome, i.iniciativa, f.id
";

$result = mysqli_query($conexao, $query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Lista de Fotos com Links</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background-color: #f4f4f4;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    a {
      color: #007bff;
    }
  </style>
</head>
<body>

<h2>Catálogo de Imagens com Links Diretos</h2>

<table>
  <tr>
    <th>Usuário</th>
    <th>Iniciativa</th>
    <th>Descrição</th>
    <th>Link da Imagem</th>
  </tr>

  <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?= htmlspecialchars($row['usuario']) ?></td>
      <td><?= htmlspecialchars($row['iniciativa']) ?></td>
      <td><?= htmlspecialchars($row['descricao']) ?></td>
      <td>
        <a href="http://192.168.1.100/sistema/uploads/<?= urlencode($row['caminho']) ?>" target="_blank">
          <?= "http://192.168.1.100/sistema/uploads/" . htmlspecialchars($row['caminho']) ?>
        </a>
      </td>
    </tr>
  <?php endwhile; ?>

</table>

</body>
</html>