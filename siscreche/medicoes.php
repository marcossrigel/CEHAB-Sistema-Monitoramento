<!DOCTYPE html>
<html lang="pt-BR">
<head>  
  <meta charset="UTF-8">
  <title>Acompanhamento de Medições</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>

  body {
    font-family: 'Poppins', sans-serif;
    background-color: #e4eaef;
    margin: 0;
    padding: 20px;
  }

  .container {
    max-width: 1200px;
    background: #fff;
    border-radius: 15px;
    margin: auto;
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
    font-weight: 400; /* ou use: normal */
  }

  .linha {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    flex-wrap: wrap;
  }

  .linha input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    flex: 1;
    min-width: 140px;
    font-family: 'Poppins', sans-serif;
  }

  label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
  }

  .button-group {
  margin-top: 20px;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
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

  .col {
    flex: 1;
    min-width: 140px;
    max-width: 180px;
  }

  .button-group button:hover {
    background-color: #3399ff;
  }

  #submit {
    background-color: #28a745;
  }

  button {
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
    font-family: 'Poppins', sans-serif;
  }

  </style>
</head>
<body>

  <div class="container">
    <form id="medicoesForm">
      <h2>Acompanhamento de Medições</h2>

      <div class="linha">
        <div class="col"><label>Valor Total da Obra</label></div>
        <div class="col"><label>Valor BM</label></div>
        <div class="col"><label>Saldo da Obra</label></div>
        <div class="col"><label>BM</label></div>
        <div class="col"><label>Data Início</label></div>
        <div class="col"><label>Data Fim</label></div>
      </div>

      <div id="linhasContainer">
        <div class="linha">
          <div><input type="text" name="valor_total[]"></div>
          <div><input type="text" name="valor_bm[]"></div>
          <div><input type="text" name="saldo_obra[]"></div>
          <div><input type="text" name="bm[]"></div>
          <div><input type="date" name="data_inicio[]"></div>
          <div><input type="date" name="data_fim[]"></div>
        </div>
      </div>
      
      </div>

      <div class="button-group">
        <button type="button" onclick="adicionarLinha()">Adicionar Linha</button>
        <button type="button" onclick="excluirLinha()">Excluir Linha</button>
        <button type="submit" name="salvar" id="submit">Salvar</button>
        <button type="button" onclick="window.location.href='visualizar.php';">&lt; Voltar</button>
      </div>

    </form>
  </div>

  <script>
    function adicionarLinha() {
      const container = document.getElementById('linhasContainer');
      const primeiraLinha = container.querySelector('.linha');
      const novaLinha = primeiraLinha.cloneNode(true);

      novaLinha.querySelectorAll('input').forEach(input => input.value = '');
      container.appendChild(novaLinha);
    }

    function excluirLinha() {
      const container = document.getElementById('linhasContainer');
      const linhas = container.querySelectorAll('.linha');

      if (linhas.length > 1) {
        container.removeChild(linhas[linhas.length - 1]);
      }
    }
  </script>

</body>
</html>