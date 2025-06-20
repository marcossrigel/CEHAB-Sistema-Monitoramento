<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistema de Monitoramento</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="assets/css/index.css" rel="stylesheet">
  <link href="assets/css/home.css" rel="stylesheet">
  <link href="assets/css/home.css" rel="stylesheet">
  <link href="assets/css/cronogramamarcos.css" rel="stylesheet">
  <link href="assets/css/visualizar.css" rel="stylesheet">

</head>
<body>

  <div class="content">
    <?php
      if ($page === 'home') {
        include_once 'home.php';
      } elseif ($page === 'crono') {
        include_once 'cronogramamarcos.php';
      } elseif ($page === 'visualizar') {
        include_once 'visualizar.php';
      }
      else {
        echo "<p style='text-align:center;'>Página não encontrada.</p>";
      }
    ?>
  </div>

</body>
</html>
