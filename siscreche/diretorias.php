<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secretarias</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #e9eef1;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .cards-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: nowrap;
        max-width: 1200px;
        overflow-x: auto;
        padding: 20px;
    }

    .card-titulo {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      line-height: 1.4;
    }

    .card-conteudo {
      width: 200px;
      height: 200px;
      background-color: #ffffff;
      border-radius: 12px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 22px;
      font-weight: bold;
      color: #1d2129;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
      cursor: pointer;
    }

    .poppins {
        font-family: 'Poppins', sans-serif;
    }

    .card-conteudo:hover {
      transform: scale(1.02);
    }

    @media (max-width: 600px) {
      .card-conteudo {
        width: 100%;
        height: 150px;
      }
    }
  </style>
</head>
<body>

<div class="cards-container">
  <div class="card-conteudo poppins">Educação</div>
  <div class="card-conteudo poppins">Saúde</div>

  <div class="card-conteudo poppins">
    <div class="card-titulo">
      <div>Infra</div>
      <div>Estratégicas</div>
    </div>
  </div>

  <div class="card-conteudo poppins">
    <div class="card-titulo">
      <div>Infra</div>
      <div>Grandes Obras</div>
    </div>
  </div>

  <div class="card-conteudo poppins">Segurança</div>
  <div class="card-conteudo poppins">Social</div>
</div>


</body>
</html>
