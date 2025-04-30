<?php
include("config.php");

$sql = "SELECT * FROM iniciativas";
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
  <meta charset="UTF-8">
  <title>Visualizar Iniciativas</title>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #e9eef1;
  padding: 40px;
}

.container {
  max-width: 800px;
  margin: auto;
}

h1 {
  text-align: center;
  margin-bottom: 30px;
}

.accordion {
  background-color: #fff;
  color: #333;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 18px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.accordion:hover {
  background-color: #f9f9f9;
}

.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
  border-radius: 0 0 10px 10px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 15px;
}

.panel p {
  margin: 10px 0;
}

.seta {
  font-size: 22px;
  transform: rotate(0deg);
  transition: transform 0.3s ease;
}

.accordion.active .seta {
  transform: rotate(180deg);
}
  </style>
</head>
<body>

<div class="container">
  <h1>Iniciativas Cadastradas</h1>

  <?php while ($row = $resultado->fetch_assoc()): ?>
    <button class="accordion">
      <strong><?php echo htmlspecialchars($row['iniciativa']); ?></strong>
      <span class="seta">⌄</span>
    </button>
    <div class="panel">
      <p><strong>Status:</strong> <?php echo $row['ib_status']; ?></p>
      <p><strong>Data da Vistoria:</strong> <?php echo $row['data_vistoria']; ?></p>
      <p><strong>Execução:</strong> <?php echo $row['ib_execucao']; ?> | 
         <strong>Previsto:</strong> <?php echo $row['ib_previsto']; ?> | 
         <strong>Variação:</strong> <?php echo $row['ib_variacao']; ?></p>
      <p><strong>Valor Médio:</strong> R$ <?php echo $row['ib_valor_medio']; ?></p>
      <p><strong>Secretaria:</strong> <?php echo $row['ib_secretaria']; ?> | 
         <strong>Órgão:</strong> <?php echo $row['ib_orgao']; ?></p>
      <p><strong>Gestor Responsável:</strong> <?php echo $row['ib_gestor_responsavel']; ?></p>
      <p><strong>Fiscal:</strong> <?php echo $row['ib_fiscal']; ?></p>
      <p><strong>Processo SEI:</strong> <?php echo $row['ib_numero_processo_sei']; ?></p>
      <p><strong>Objeto:</strong> <?php echo $row['objeto']; ?></p>
      <p><strong>Informações Gerais:</strong> <?php echo $row['informacoes_gerais']; ?></p>
      <p><strong>Observações:</strong> <?php echo $row['observacoes']; ?></p>
    </div>
  <?php endwhile; ?>
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
