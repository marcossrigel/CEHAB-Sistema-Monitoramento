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

.table-container {
  background: #fff;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  min-width: 600px;
}

.main-title {
  font-size: 32px;
  color: #000;
  text-align: center;
  margin-bottom: 20px;
}

.container {
  max-width: 800px;
  margin: auto;
}

table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 15px;
}

th, td {
  text-align: left;
  padding: 10px;
}

td {
  padding-right: 15px;
}

input[type="text"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-sizing: border-box;
}

.button-group {
  margin-top: 20px;
  display: flex;
  justify-content: space-around;
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

.button {
  margin-top: 20px;
  display: flex;
  justify-content: flex-start;
  gap: 10px;
}
.button button {
  padding: 10px 20px;
  background-color: #4da6ff;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s ease;
}
.button button:hover {
  background-color: #3399ff;
}

.botao-voltar {
  display: flex;
  justify-content: center;
  margin-top: 40px;
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
  <h1>Iniciativas Cadastradas</h1>

  <?php while ($row = $resultado->fetch_assoc()): ?>
    <button class="accordion">
      <strong><?php echo htmlspecialchars($row['iniciativa']); ?></strong>
      <span class="seta">⌄</span>
    </button>
    <div class="panel">
      <p>
        <strong>Status:</strong> <?php echo $row['ib_status']; ?> |
        <strong>Data da Vistoria:</strong> <?php echo $row['data_vistoria']; ?>
      </p>
      
      <p>
        <strong>Execução:</strong> <?php echo $row['ib_execucao']; ?> | 
        <strong>Previsto:</strong> <?php echo $row['ib_previsto']; ?> | 
        <strong>Variação:</strong> <?php echo $row['ib_variacao']; ?> |
        <strong>Valor Médio:</strong> R$ <?php echo $row['ib_valor_medio']; ?>
      </p>
      
      <p><strong>Secretaria:</strong> <?php echo $row['ib_secretaria']; ?> |
        <strong>Órgão:</strong> <?php echo $row['ib_orgao']; ?> |
        <strong>Processo SEI:</strong> <?php echo $row['ib_numero_processo_sei']; ?>
      </p>
      
      <p><strong>Gestor Responsável:</strong> <?php echo $row['ib_gestor_responsavel']; ?> | 
        <strong>Fiscal:</strong> <?php echo $row['ib_fiscal']; ?>
      </p>
      
      <br>
        <p><strong>Objeto:</strong> <?php echo $row['objeto']; ?></p>
        <p><strong>Informações Gerais:</strong> <?php echo $row['informacoes_gerais']; ?></p>
        <p><strong>Observações:</strong> <?php echo $row['observacoes']; ?></p>
      <br>

      <div class="button">
      <button onclick="window.location.href='home.php';">editar</button>
      </div>

      <hr>
      <br>

      <button onclick="window.location.href='acompanhamento.php';" style="background-color:#4da6ff; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:bold; cursor:pointer; margin-bottom:15px;">
        Acompanhar Pendências
      </button>

      <button style="background-color:#4da6ff; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:bold; cursor:pointer; margin-bottom:15px;">
        Informações Contratuais
      </button>

      <button style="background-color:#4da6ff; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:bold; cursor:pointer; margin-bottom:15px;">
        Acompanhamento de Medições
      </button>
      <br>

      <button style="background-color:#4da6ff; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:bold; cursor:pointer; margin-bottom:15px;">
        Cronograma de Macros
      </button>

      <button style="background-color:#4da6ff; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:bold; cursor:pointer; margin-bottom:15px;">
        Acompanhamento Fotográfico
      </button>

    </div>

  <?php endwhile; ?>
    
  <div class="botao-voltar">
    <button class="btn-azul" onclick="window.location.href='home.php';">&lt; Voltar</button>
  </div>

</div>
</body>
</html>

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