<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Acompanhamento de Medições</title>
  <link href="https://fonts.googleapis.com/css2?family=Arial&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e9eff3;
      padding: 30px;
      display: flex;
      justify-content: center;
    }

    .container {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 90%;
      max-width: 1100px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #b6dfb6;
      color: black;
    }

    td input {
      width: 100%;
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      text-align: center;
    }

    .button-group {
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .button-group button {
      padding: 10px 16px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .azul {
      background-color: #4da6ff;
      color: white;
    }

    .azul:hover {
      background-color: #3399ff;
    }

    .verde {
      background-color: #2ab300;
      color: white;
    }

    .verde:hover {
      background-color: #219100;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Acompanhamento de Medições</h2>
    <form>
      <table id="medicoes-tabela">
        <thead>
          <tr>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Saldo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" value="VALOR DO ORÇAMENTO DA OBRA"></td>
            <td><input type="text" placeholder="R$ 0,00"></td>
            <td><input type="text" placeholder="R$ 0,00"></td>
          </tr>
          <tr>
            <td><input type="text" value="BOLETIM DE MEDIÇÃO 01 (27/02/2025 À 02/04/2025)"></td>
            <td><input type="text" placeholder="R$ 0,00"></td>
            <td><input type="text" placeholder="R$ 0,00"></td>
          </tr>
        </tbody>
      </table>

      <div class="button-group">
        <button type="button" class="btn azul" onclick="adicionarLinha()">Adicionar Linha</button>
        <button type="button" class="btn azul" onclick="removerLinha()">Excluir Linha</button>
        <button type="button" class="btn azul" onclick="window.location.href='visualizar.php';"> < Voltar</button>
        <button type="submit" class="btn verde">Salvar</button>
      </div>
    </form>
  </div>

  <script>
    function adicionarLinha() {
      const tabela = document.getElementById("medicoes-tabela").getElementsByTagName("tbody")[0];
      const novaLinha = tabela.insertRow();

      for (let i = 0; i < 3; i++) {
        const novaCelula = novaLinha.insertCell();
        novaCelula.innerHTML = '<input type="text" placeholder="Digite...">';
      }
    }

    function removerLinha() {
      const tabela = document.getElementById("medicoes-tabela").getElementsByTagName("tbody")[0];
      if (tabela.rows.length > 1) {
        tabela.deleteRow(-1);
      }
    }
  </script>
</body>
</html>
