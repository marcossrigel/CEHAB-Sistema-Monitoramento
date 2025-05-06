<?php
  session_start();
  if (!isset($_SESSION['id_usuario'])) {
      header('Location: login.php');
      exit;
  }

  if(isset($_POST['submit']))
  {
    include_once('config.php');
    $iniciativa = trim($_POST['iniciativa']);
    $check_query = "SELECT * FROM iniciativas WHERE iniciativa = '$iniciativa'";
    $check_result = mysqli_query($conexao, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Erro: Já existe uma iniciativa com esse nome.'); window.history.back();</script>";
        exit;
    }
    
    $data_vistoria = $_POST['data_vistoria'];
    $ib_status = $_POST['ib_status'];
    $ib_execucao = $_POST['ib_execucao'];
    $ib_previsto = $_POST['ib_previsto'];
    $ib_variacao = $_POST['ib_variacao'];
    $ib_valor_medio = $_POST['ib_valor_medio'];
    $ib_secretaria = $_POST['ib_secretaria'];
    $ib_orgao = $_POST['ib_orgao'];
    $ib_gestor_responsavel = $_POST['ib_gestor_responsavel'];
    $ib_fiscal = $_POST['ib_fiscal'];
    $ib_numero_processo_sei = $_POST['ib_numero_processo_sei'];
    $objeto = $_POST['objeto'];
    $informacoes_gerais = $_POST['informacoes_gerais'];
    $observacoes = $_POST['observacoes'];
    
    $id_usuario = $_SESSION['id_usuario'];
    
    $result = mysqli_query($conexao, "INSERT INTO iniciativas(id_usuario,iniciativa,data_vistoria,ib_status,ib_execucao,ib_previsto,ib_variacao,ib_valor_medio,ib_secretaria,ib_orgao,ib_gestor_responsavel,ib_fiscal,ib_numero_processo_sei,objeto,informacoes_gerais,observacoes)
    
    VALUES ('$id_usuario', '$iniciativa','$data_vistoria','$ib_status','$ib_execucao','$ib_previsto','$ib_variacao','$ib_valor_medio','$ib_secretaria','$ib_orgao','$ib_gestor_responsavel','$ib_fiscal','$ib_numero_processo_sei','$objeto','$informacoes_gerais','$observacoes')");
    header("Location: formulario.php?sucesso=1&nome=" . urlencode($iniciativa));
    exit;
    
  }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="formulario.css">
  <title>Formulário</title>
  <style>
    :root {
  --color-white: rgb(255, 255, 255);
  --color-gray: #e3e8ec;
  --color-dark: #1d2129;
  --color-blue: #0a6be2;
  --color-green: #42b72a;
}

body {
  font-family: Arial, sans-serif;
  background-color: var(--color-gray);
  display: flex;
  justify-content: center;
  margin: 0;
  padding: 40px 20px;
  box-sizing: border-box;
}

.formulario {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  width: 1000px;
}

.main-title {
  text-align: center;
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

.label {
  display: block;
  font-size: 14px;
  color: #333;
  margin-bottom: 4px;
  margin-left: 2px;
  font-weight: bold;
}

.linha {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
  flex-wrap: nowrap;
}

.campo-pequeno {
  flex: 1;
  min-width: 200px;
  display: flex;
  flex-direction: column;
}

.campo {
  flex: 1;
  min-width: 150px;
}

.campo-longo {
  flex: 2; 
  min-width: 200px;
}

.linha-atividade {
  display: flex;
  gap: 20px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.coluna-textarea {
  flex: 2;
  display: flex;
  flex-direction: column;
}

.coluna-textarea textarea {
  width: 98%;
  height: 100px;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  resize: vertical;
  font-size: 14px;
  font-family: inherit;
}

button.btn {
  background-color: var(--color-green);
  color: var(--color-white);
  text-decoration: none;
  padding: 14px 20px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  transition: background 0.3s;
  width: 200px;
  display: inline-block;
  border: none;
  outline: none;
  cursor: pointer;
  margin: 20px auto;   
  display: block;
}

button.btn:hover {
  background-color: #36a420;
}

.texto-login {
  text-align: center;
  color: var(--color-blue);
  font-size: 14px;
  margin-top: 20px auto;
  display: block;
}

a.texto-login{
  color: red;               
  text-decoration: none;  
  font-weight: bold;
}

a.texto-login:hover {
  text-decoration: none;
}

input[type="text"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  box-sizing: border-box;
}

select[type="text"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  box-sizing: border-box;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.modal-content {
  background-color: white;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
  max-width: 400px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.hidden {
  display: none;
}

.modal-content button {
  margin-top: 20px;
  padding: 10px 20px;
  border: none;
  background-color:#42b72a;
  color: white;
  border-radius: 6px;
  cursor: pointer;
}

.modal-content button:hover {
  background-color: #42b72a;
}

  </style>
</head>

<body>
    
  <form class="formulario" action="formulario.php" method="post">
    <h1 class="main-title">Criar uma nova iniciativa</h1>

    <div class="linha">
      <div class="campo-pequeno">
        <label class="label">Nome da Iniciativa</label>
        
        <select type="text" name="iniciativa" class="campo" required>
          <option>Selecione...</option>
          <option>Cabrobó</option>
          <option>Granito</option>
          <option>Lagoa Grande</option>
          <option>Ouricuri</option>
          <option>Mirandiba</option>
        </select>
      </div>
      <div class="campo-pequeno">
        <label class="label">Data da Vistoria</label>
        <input type="date" name="data_vistoria" class="campo" required>
      </div>
    </div>

    <div class="linha">
      <div class="campo">
        <label class="label">Informações Básicas: </label>
      </div>
    </div>

    <div class="linha">
      <div class="campo">
        <label class="label">Status</label>
        
        <select type="text" name="ib_status" class="campo">
          <option>Selecione...</option>
          <option>Em Execução</option>
          <option>Em Andamento</option>
          <option>Liberado</option>
        </select>
      </div>
      <div class="campo">
        <label class="label">Execução</label>
        <input type="text" name="ib_execucao">
      </div>
      <div class="campo">
        <label class="label">Previsto</label>
        <input type="text" name="ib_previsto">
      </div>
      <div class="campo">
        <label class="label">Variação</label>
        <input type="text" name="ib_variacao">
      </div>
      <div class="campo-longo">
        <label class="label">Valor Médio Acumulado</label>
        <input type="text" name="ib_valor_medio">
      </div>
    </div>    

    <div class="linha">
      <div class="campo">
        <label class="label">Secretaria</label>
        <input type="text" name="ib_secretaria">
      </div>
      <div class="campo">
        <label class="label">Órgão</label>
        <input type="text" name="ib_orgao">
      </div>
      
      <div class="campo">
        <label class="label">Gestor Responsável</label>
        <input type="text" name="ib_gestor_responsavel">
      </div>

      <div class="campo">
        <label class="label">Fiscal Responsável</label>
        <input type="text" name="ib_fiscal">
      </div>
    
      <div class="campo-longo">
        <label class="label">Nº Processo SEI</label>
        <input type="text" name="ib_numero_processo_sei">
      </div>
    </div>  

    <div class="linha-atividade">
      <div class="coluna-textarea">
        <label class="label">OBJETO</label>
        <textarea name="objeto"></textarea>
      </div>
    </div>

    <br>
    <hr>

    <div class="linha-atividade">
      <div class="coluna-textarea">
        <label class="label">Informações Gerais</label>
        <textarea name="informacoes_gerais"></textarea>
      </div>
    </div>

    <div class="linha-atividade">
      <div class="coluna-textarea">
        <label class="label">OBSERVAÇÕES (PONTOS CRÍTICOS)</label>
        <textarea name="observacoes"></textarea>
      </div>
    </div>

    <br>

    <button type="submit" name="submit" id="submit" class="btn btn-create-account">Criar</button>
    <a href="#" class="texto-login" onclick="confirmarCancelamento(event)">Cancelar</a>
  </form>

  <?php
    $mensagem = '';
    if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1 && isset($_GET['nome'])) {
        $mensagem = 'Iniciativa "' . htmlspecialchars($_GET['nome']) . '" criada com sucesso!';
    }
  ?>

  <div id="modal" class="modal hidden">
    <div class="modal-content">
      <p id="modal-message"></p>
      <button style="background-color: #42b72a;" onclick="voltarParaHome()">Voltar</button>
    </div>
  </div>

  <div id="modal-cancelar" class="modal hidden">
    <div class="modal-content">
      <p>Você deseja cancelar? Os dados preenchidos podem ser perdidos.</p>
      <button id="btn-sim" style="background-color: #dc3545;">Sim</button>
      <button id="btn-nao" style="background-color: #6c757d; margin-left: 10px;">Não</button>
    </div>
  </div>

</body>

<script>
  function showModal(message) {
    document.getElementById('modal-message').innerText = message;
    document.getElementById('modal').classList.remove('hidden');
  }

  function closeModal() {
    document.getElementById('modal').classList.add('hidden');
  }

  function temCamposPreenchidos() {
    const inputs = document.querySelectorAll('.formulario input, .formulario textarea, .formulario select');
    return Array.from(inputs).some(input => input.value.trim() !== '');
  }

  function confirmarCancelamento(event) {
    event.preventDefault();

    if (temCamposPreenchidos()) {
      document.getElementById('modal-cancelar').classList.remove('hidden');
    } else {
      window.location.href = 'home.php';
    }
  }

  function voltarParaHome() {
    window.location.href = 'home.php';
  }

document.getElementById('btn-sim').addEventListener('click', function() {
  window.location.href = 'home.php';
});

document.getElementById('btn-nao').addEventListener('click', function() {
  document.getElementById('modal-cancelar').classList.add('hidden');
});

document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($mensagem)) { ?>
      showModal(`<?php echo addslashes($mensagem); ?>`);
    <?php } ?>
});

</script>

</html>