<?php
session_start();
include_once("config.php");

$id_iniciativa = isset($_GET['id_iniciativa']) ? intval($_GET['id_iniciativa']) : 0;

$fotos_salvas = [];
$query = "SELECT * FROM fotos WHERE id_iniciativa = $id_iniciativa";
$result = mysqli_query($conexao, $query);
while ($linha = mysqli_fetch_assoc($result)) {
    $fotos_salvas[] = $linha;
}

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

    echo "<script>alert('Fotos enviadas com sucesso!'); window.location.href='fotografico.php?id_iniciativa=$id_iniciativa';</script>";
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

.linha-fotos {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 30px;
}

.foto-box {
    flex: 0 0 23%;
    max-width: 23%;
    background: #fafafa;
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 8px;
    text-align: center;
    min-width: 200px;
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
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: bold;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    background-color: #4da6ff;
    color: white;
    max-width: 150px; 
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
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.button-group button {
    padding: 6px 14px;
    width: 150px;
    height: 30px;
    font-size: 13px;
    font-weight: bold;
    border-radius: 8px;
    border: none;
    background-color: #4da6ff;
    color: white;
    cursor: pointer;
    white-space: nowrap;
}
.button-group button:hover {
    background-color: #3399ff;
}
.preview {
  width: 100%;
  height: auto;
  border-radius: 6px;
  margin-top: 5px;
  object-fit: cover;
}
  </style>
</head>
<body>
  <div class="container">
    <h2>Enviar Fotos - Iniciativa <?php echo $id_iniciativa; ?></h2>
    <form method="post" enctype="multipart/form-data" id="form-fotos">

      <div id="linhas-container">
        <div class="linha-fotos">
            <?php for ($i = 0; $i < 4; $i++): ?>
            <div class="foto-box">
                <p><strong>Foto</strong></p>
                <input type="file" name="foto[]" class="input-foto" accept="image/*">
                <img src="" class="preview" style="display:none;">
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

    document.addEventListener("change", function(e) {
    if (e.target && e.target.classList.contains("input-foto")) {
    const fileInput = e.target;
    const file = fileInput.files[0];
    const previewImg = fileInput.parentElement.querySelector(".preview");

        if (file && previewImg) {
            const reader = new FileReader();
            reader.onload = function(event) {
            previewImg.src = event.target.result;
            previewImg.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    }
});

    function adicionarLinha() {
      const container = document.getElementById('linhas-container');

      const novaLinha = document.createElement('div');
      novaLinha.className = 'linha-fotos';

      for (let i = 0; i < 4; i++) {
        const box = document.createElement('div');
        box.className = 'foto-box';

        
        box.innerHTML = `
            <p><strong>Foto</strong></p>
            <input type="file" name="foto[]" class="input-foto" accept="image/*">
            <img src="" class="preview" style="display:none;">
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
        } 
    }
  </script>
</body>
</html>