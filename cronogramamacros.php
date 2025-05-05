<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cronograma de Marcos</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }
    .container {
      width: 100%;
      max-width: 1200px;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    h1 {
      margin-bottom: 20px;
      font-size: 24px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #dee2e6;
      text-align: left;
      vertical-align: top;
    }
    th {
      background-color: #f1f3f5;
    }
    td.etapa {
      width: 25%;
    }
    textarea.no-border {
      width: calc(100% - 10px);
      height: 60px;
      resize: vertical;
      border: none;
      outline: none;
      font-family: 'Arial', sans-serif;
      font-size: 14px;
      padding: 5px;
    }
    input {
      width: calc(100% - 10px);
      border: 1px solid #ccc;
      padding: 6px;
      border-radius: 4px;
      box-sizing: border-box;
    }
    .subtitle-row input {
      font-weight: 600;
      background-color: #f8f9fa;
      color: #495057;
    }
    .button-group {
      display: flex;
      justify-content: center;
      gap: 15px;
    }
    .button-group button {
      background-color: #339af0;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .button-group button:hover {
      background-color: #1c7ed6;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>CRONOGRAMA DE MARCOS</h1>
    <table id="cronogramaTable">
      <thead>
        <tr>
          <th>Etapa</th>
          <th>Início Previsto</th>
          <th>Término Previsto</th>
          <th>Início Real</th>
          <th>Término Real</th>
          <th>% Evolutivo</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    <div class="button-group">
      <button onclick="adicionarLinha()">Adicionar Linha</button>
      <button onclick="adicionarSubtitulo()">Adicionar Subtítulo</button>
      <button onclick="excluirLinha()">Excluir Linha</button>
      <button>&lt; Voltar</button>
    </div>
  </div>

  <script>
    function adicionarLinha(classeExtra = '') {
      const table = document.getElementById('cronogramaTable').getElementsByTagName('tbody')[0];
      const newRow = table.insertRow();
      if (classeExtra) newRow.classList.add(classeExtra);

      const etapaCell = newRow.insertCell(0);
      etapaCell.className = 'etapa';
      if (classeExtra === 'subtitle-row') {
        etapaCell.innerHTML = `<input type="text">`;
      } else {
        etapaCell.innerHTML = `<textarea class="no-border"></textarea>`;
      }

      for (let i = 1; i < 6; i++) {
        const cell = newRow.insertCell(i);
        cell.innerHTML = `<input type="text">`;
      }
    }

    function adicionarSubtitulo() {
      adicionarLinha('subtitle-row');
    }

    function excluirLinha() {
      const table = document.getElementById('cronogramaTable').getElementsByTagName('tbody')[0];
      if (table.rows.length > 0) {
        table.deleteRow(-1);
      }
    }
  </script>
</body>
</html>
