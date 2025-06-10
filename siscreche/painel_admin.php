<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include("config.php");

// Pega TODAS as iniciativas, pois é um painel de administrador
$sql = "SELECT iniciativas.*, usuarios.nome AS nome_usuario 
        FROM iniciativas 
        INNER JOIN usuarios ON iniciativas.id_usuario = usuarios.id_usuario 
        ORDER BY iniciativas.id DESC";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Administrador</title>
  <style>
    <?php include 'visualizar.css'; // Caso tenha separado o CSS num arquivo externo ?>
  </style>
</head>
<body>
<div class="container">
  
  <div class="topo-linha">
    <div class="voltar-box">
      <button onclick="window.location.href='home.php';">< Voltar</button>
    </div>
    <h1>Painel do Administrador</h1>
  </div>

  <?php while ($row = $resultado->fetch_assoc()): ?>
    <button class="accordion">
      <strong><?php echo htmlspecialchars($row['iniciativa']); ?></strong>
      <span class="seta">⌄</span>
    </button>
    
    <div class="panel">
      <p><strong><u>Usuário:</u></strong> <?php echo htmlspecialchars($row['nome_usuario']); ?></p>
      <p><strong>Status:</strong> <?php echo $row['ib_status']; ?> | <strong>Data da Vistoria:</strong> <?php echo $row['data_vistoria']; ?></p>
      <p><strong>Execução:</strong> <?php echo $row['ib_execucao']; ?> | <strong>Previsto:</strong> <?php echo $row['ib_previsto']; ?> | <strong>Variação:</strong> <?php echo $row['ib_variacao']; ?> | <strong>Valor Médio:</strong> R$ <?php echo $row['ib_valor_medio']; ?></p>
      <p><strong>Secretaria:</strong> <?php echo $row['ib_secretaria']; ?> | <strong>Órgão:</strong> <?php echo $row['ib_orgao']; ?> | <strong>Processo SEI:</strong> <?php echo $row['ib_numero_processo_sei']; ?></p>
      <p><strong>Gestor Responsável:</strong> <?php echo $row['ib_gestor_responsavel']; ?> | <strong>Fiscal Responsável:</strong> <?php echo $row['ib_fiscal']; ?></p>
      <p><strong>Objeto:</strong> <?php echo $row['objeto']; ?></p>
      <p><strong>Informações Gerais:</strong> <?php echo $row['informacoes_gerais']; ?></p>
      <p><strong>Observações:</strong> <?php echo $row['observacoes']; ?></p>

      <div class="button-left">
        <button onclick="window.location.href='editar_iniciativa.php?id=<?php echo $row['id']; ?>';">Status andamento</button>
      </div>

      <div class="acoes">
        <button onclick="window.location.href='acompanhamento.php?id_iniciativa=<?php echo $row['id']; ?>';">🛠 Acompanhar Pendências</button>
        <button onclick="window.location.href='infocontratuais.php?id_iniciativa=<?php echo $row['id']; ?>';">📄 Informações Contratuais</button>
        <button onclick="window.location.href='medicoes.php?id_iniciativa=<?php echo $row['id']; ?>';">📊 Acompanhamento de Medições</button>
        <button onclick="window.location.href='cronogramamarcos.php?id_iniciativa=<?php echo $row['id']; ?>';">📆 Cronograma de Marcos</button>
        <button onclick="window.location.href='fotografico.php?id_iniciativa=<?php echo $row['id']; ?>';">📷 Fotografias</button>
        <button onclick="window.location.href='galeria.php?id_iniciativa=<?php echo $row['id']; ?>';">💾 Galeria</button>
      </div>
    </div>
  <?php endwhile; ?>

  <div class="botao-voltar">
    <button onclick="window.location.href='home.php';">&lt; Voltar</button>
  </div>
</div>

<script>
  const accordions = document.querySelectorAll(".accordion");
  accordions.forEach((acc) => {
    acc.addEventListener("click", function () {
      this.classList.toggle("active");
      const panel = this.nextElementSibling;
      if (panel.style.display === "block") {
        panel.style.display = "none";
      } else {
        panel.style.display = "block";
      }
    });
  });
</script>
</body>
</html>
