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
      <p><strong>Status:</strong> <?php echo $row['ib_status']; ?> |
      <strong>Data da Vistoria:</strong> <?php echo $row['data_vistoria']; ?></p>
      
      <p><strong>Execução:</strong> <?php echo $row['ib_execucao']; ?> | 
        <strong>Previsto:</strong> <?php echo $row['ib_previsto']; ?> | 
        <strong>Variação:</strong> <?php echo $row['ib_variacao']; ?> |
        <strong>Valor Médio:</strong> R$ <?php echo $row['ib_valor_medio']; ?>
      </p>
      
      <p><strong>Secretaria:</strong> <?php echo $row['ib_secretaria']; ?> |
        <strong>Órgão:</strong> <?php echo $row['ib_orgao']; ?> |
        <strong>Processo SEI:</strong> <?php echo $row['ib_numero_processo_sei']; ?>
      </p>
      
      <p><strong>Gestor Responsável:</strong> <?php echo $row['ib_gestor_responsavel']; ?>
        <strong>Fiscal:</strong> <?php echo $row['ib_fiscal']; ?>
      </p>
      
      <br>
        <p><strong>Objeto:</strong> <?php echo $row['objeto']; ?></p>
        <p><strong>Informações Gerais:</strong> <?php echo $row['informacoes_gerais']; ?></p>
        <p><strong>Observações:</strong> <?php echo $row['observacoes']; ?></p>
      <br>

      <hr>
      <br>

      <button onclick="abrirModal()" style="background-color:#4da6ff; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:bold; cursor:pointer; margin-bottom:15px;">
        Acompanhar Pendências
      </button>
      <br>

      <button onclick="window.location.href='acompanhamento.php';" style="background-color:#4da6ff; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:bold; cursor:pointer; margin-bottom:15px;">
        Informações Contratuais
      </button>

    </div>
  <?php endwhile; ?>
</div>

  <!-- MODAL ACOMPANHAMENTO DE PENDENCIAS -->

  <div id="modalPendencias" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div class="table-container" style="position:relative;">
      <span onclick="fecharModal()" style="position:absolute; top:10px; right:15px; font-size:20px; cursor:pointer;">&times;</span>
      <div class="main-title">Acompanhamento de Pendências</div>
      <table id="spreadsheet">
        <thead>
          <tr>
            <th>Problema</th>
            <th>Contramedida</th>
            <th>Prazo</th>
            <th>Responsável</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" placeholder=""></td>
            <td><input type="text" placeholder=""></td>
            <td><input type="text" placeholder=""></td>
            <td><input type="text" placeholder=""></td>
          </tr>
        </tbody>
      </table>
      <div class="button-group">
        <button onclick="addRow()">Adicionar Linha</button>
        <button onclick="deleteRow()">Excluir Linha</button>
        <button onclick="saveData()">Salvar</button>
      </div>
    </div>
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

  function abrirModal() {
    document.getElementById('modalPendencias').style.display = 'flex';
  }

  function fecharModal() {
    document.getElementById('modalPendencias').style.display = 'none';
  }

  function addRow() {
    const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();
    for (let i = 0; i < 4; i++) {
      const newCell = newRow.insertCell();
      const input = document.createElement('input');
      input.type = 'text';
      newCell.appendChild(input);
    }
  }

  function deleteRow() {
    const table = document.getElementById('spreadsheet').getElementsByTagName('tbody')[0];
    if (table.rows.length > 1) {
      table.deleteRow(-1);
    }
  }

</script>