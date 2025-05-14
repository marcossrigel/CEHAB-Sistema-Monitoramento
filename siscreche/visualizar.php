<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include("config.php");

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM iniciativas WHERE id_usuario = $id_usuario";
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

.acoes {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 20px;
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

.acoes button {
  padding: 10px 20px;
  font-weight: bold;
  font-size: 14px;
  border: none;
  border-radius: 12px;
  color: white;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.acoes .btn-pendencia,
.acoes .btn-contrato,
.acoes .btn-medicoes,
.acoes .btn-marcos,
.acoes .btn-fotos {
  background-color: #4da6ff; /* ✅ igual ao botão azul padrão */
  color: white;
  border-radius: 10px;
}

.acoes button:hover {
  filter: brightness(1.1);
  transform: scale(1.03);
}

.button button:hover,
.acoes button:hover {
  transform: scale(1.03);
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
        <strong>Fiscal Responsável:</strong> <?php echo $row['ib_fiscal']; ?>
      </p>
      
      <br>
        <p><strong>Objeto:</strong> <?php echo $row['objeto']; ?></p>
        <p><strong>Informações Gerais:</strong> <?php echo $row['informacoes_gerais']; ?></p>
        <p><strong>Observações:</strong> <?php echo $row['observacoes']; ?></p>
      <br>

      <div style="display: flex; justify-content: space-between; align-items: center;">
      <div class="button">
        <button onclick="window.location.href='editar_iniciativa.php?id=<?php echo $row['id']; ?>';">status andamento</button>
      </div>
      
      
      </div>

      <hr>

      <div class="acoes">
        <button class="btn-pendencia" onclick="window.location.href='acompanhamento.php?id_iniciativa=<?php echo $row['id']; ?>';">🛠 Acompanhar Pendências</button>
        <button class="btn-contrato" onclick="window.location.href='infocontratuais.php?id_iniciativa=<?php echo $row['id']; ?>';">📄 Informações Contratuais</button>
        <button class="btn-medicoes" onclick="window.location.href='medicoes.php';">📊 Medições</button>
        <button class="btn-marcos" onclick="window.location.href='cronogramamarcos.php';">📆 Cronograma</button>
        <button class="btn-fotos" onclick="window.location.href='fotografico.php?id_iniciativa=<?php echo $row['id']; ?>';">📷 Fotografias</button>
      </div>
      <br>

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