<?php
session_start();
include_once("config.php");

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_FILES['foto']['tmp_name'] as $i => $tmp) {
        if (!empty($_FILES['foto']['name'][$i])) {
            $descricao = mysqli_real_escape_string($conexao, $_POST['descricao'][$i]);
            $nome_final = time() . '_' . basename($_FILES['foto']['name'][$i]);
            $caminho = "uploads/" . $nome_final;

            if (move_uploaded_file($tmp, $caminho)) {
                $query = "INSERT INTO fotos (id_iniciativa, caminho, descricao) VALUES ($id_iniciativa, '$nome_final', '$descricao')";
                mysqli_query($conexao, $query);
            }
        }
    }

    echo "<script>alert('Fotos enviadas com sucesso!'); window.location.href='acompanhamento_fotos.php?id_iniciativa=$id_iniciativa';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Upload de Fotos</title>
  <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f1f1f1;
    padding: 30px;
}

.container {
    max-width: 1200px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
}

.linha-fotos {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: nowrap;
}

.foto-box {
    flex: 1;
    min-height: 220px;
    background: #fafafa;
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 10px;
    text-align: center;
}

.foto-box input[type="file"] {
    display: block;
    margin: 10px auto;
}

.foto-box textarea {
    width: 100%;
    height: 60px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none;
    padding: 5px;
}

button {
    display: block;
    width: 100%;
    background-color: #4da6ff;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    margin-top: 15px;
    cursor: pointer;
}
.btn {
padding: 8px 16px;
border: none;
border-radius: 10px;
font-size: 14px;
font-weight: bold;
cursor: pointer;
}

button:hover {
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
  </style>
</head>
<body>
  <div class="container">
    <h2>Enviar Fotos - Iniciativa <?php echo $id_iniciativa; ?></h2>
    <form method="post" enctype="multipart/form-data" id="form-fotos">

      <div id="linhas-container">
        <!-- 1ª linha inicial -->
        <div class="linha-fotos">
          <?php for ($i = 0; $i < 4; $i++): ?>
            <div class="foto-box">
              <p><strong>Foto</strong></p>
              <input type="file" name="foto[]">
              <textarea name="descricao[]" placeholder="Descrição da foto"></textarea>
            </div>
          <?php endfor; ?>
        </div>
      </div>

    <div class="button-group">
        <button type="button" class="btn azul" onclick="adicionarLinha()">Adicionar Linha</button>
        <button type="button" class="btn azul" onclick="removerLinha()">Excluir Linha</button>
        <button type="submit" class="btn verde" style="background-color:rgb(42, 179, 0);" >Salvar</button>
        <button type="button" class="btn azul" onclick="window.location.href='visualizar.php?id_iniciativa=<?php echo $id_iniciativa; ?>';">&lt; Voltar</button>
    </div>

    </form>
  </div>

  <script>
    function adicionarLinha() {
      const container = document.getElementById('linhas-container');

      const novaLinha = document.createElement('div');
      novaLinha.className = 'linha-fotos';

      for (let i = 0; i < 4; i++) {
        const box = document.createElement('div');
        box.className = 'foto-box';

        box.innerHTML = `
          <p><strong>Foto</strong></p>
          <input type="file" name="foto[]">
          <textarea name="descricao[]" placeholder="Descrição da foto"></textarea>
        `;

        novaLinha.appendChild(box);
      }

      container.appendChild(novaLinha);
    }

    function removerLinha() {
        const container = document.getElementById('linhas-container');
        const linhas = container.getElementsByClassName('linha-fotos');
        if (linhas.length > 1) {
            container.removeChild(linhas[linhas.length - 1]);
        } else {
            alert("Você precisa manter pelo menos uma linha.");
        }
    }
  </script>
</body>
</html>
