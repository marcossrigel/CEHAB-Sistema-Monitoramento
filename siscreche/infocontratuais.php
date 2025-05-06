<?php
  session_start();
  if (!isset($_SESSION['id_usuario'])) {
      header('Location: login.php');
      exit;
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informações Contratuais</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --color-white: #ffffff;
  --color-gray: #e3e8ec;
  --color-dark: #1d2129;
  --color-blue: #4da6ff;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: var(--color-gray);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  padding: 30px 0;
}

.container {
  background: var(--color-white);
  padding: 40px 30px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  width: 90%;
  max-width: 900px;
}

.main-title {
  font-size: 28px;
  font-weight: 600;
  color: var(--color-dark);
  text-align: center;
  margin-bottom: 30px;
}

table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
}

th, td {
  text-align: left;
  padding: 8px;
}

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 15px;
}

.button-group {
  margin-top: 30px;
  display: flex;
  justify-content: center;
  gap: 15px;
}

button {
  padding: 12px 20px;
  background-color: var(--color-blue);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #3399ff;
}
</style>

</head>

<body>
  <div class="container">
    <div class="main-title">Informações Contratuais</div>
    <table>
      <tr>
        <th>Campo</th>
        <th>Valor</th>
      </tr>
      <tr>
        <td>Processo Licitatório</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Empresa</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Data Assinatura do Contrato</td>
        <td><input type="date"></td>
      </tr>
      <tr>
        <td>Data da O.S.</td>
        <td><input type="date"></td>
      </tr>
      <tr>
        <td>Prazo de Execução Original</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Prazo de Execução Atual</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Valor Inicial da Obra</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Valor Total da Obra</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Valor Inicial do Contrato</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Valor do Aditivo</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Valor Total do Contrato</td>
        <td><input type="text"></td>
      </tr>
      <tr>
        <td>Secretaria Demandante</td>
        <td><input type="text"></td>
      </tr>
    </table>
    <div class="button-group">
      <button>Salvar</button>
      <button>Cancelar</button>
    </div>
  </div>
</body>
</html>
