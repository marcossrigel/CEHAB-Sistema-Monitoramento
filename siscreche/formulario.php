<?php

  if(isset($_POST['submit']))
  {
    include_once('config.php');
    $iniciativa = trim($_POST['iniciativa']);
    if (empty($iniciativa)) {
        echo "<script>alert('Erro: O campo Iniciativa é obrigatório.'); window.history.back();</script>";
        exit;
    }

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

    $result = mysqli_query($conexao, "INSERT INTO iniciativas(iniciativa,data_vistoria,ib_status,ib_execucao,ib_previsto,ib_variacao,ib_valor_medio,ib_secretaria,ib_orgao,ib_gestor_responsavel,ib_fiscal,ib_numero_processo_sei,objeto,informacoes_gerais,observacoes)
    VALUES ('$iniciativa','$data_vistoria','$ib_status','$ib_execucao','$ib_previsto','$ib_variacao','$ib_valor_medio','$ib_secretaria','$ib_orgao','$ib_gestor_responsavel','$ib_fiscal','$ib_numero_processo_sei','$objeto','$informacoes_gerais','$observacoes')");
  }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="formulario.css">
  <title>Formulário</title>
</head>

<body>

  <div id="modal" class="modal hidden">
    <div class="modal-content">
      <p id="modal-message"></p>
      <button onclick="closeModal()">Fechar</button>
    </div>
  </div>
  
  <form class="formulario" action="formulario.php" method="post">
    <h1 class="main-title">Criar uma nova iniciativa</h1>

    <div class="linha">
      <div class="campo-pequeno">
        <label class="label">Nome da Iniciativa</label>
        <input type="text" name="iniciativa" class="campo" required>
      </div>
      <div class="campo-pequeno">
        <label class="label">Data da Vistoria</label>
        <input type="text" name="data_vistoria" class="campo">
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
        <label class="label">Fiscal</label>
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

document.getElementById('btn-sim').addEventListener('click', function() {
  window.location.href = 'home.php';
});

document.getElementById('btn-nao').addEventListener('click', function() {
  document.getElementById('modal-cancelar').classList.add('hidden');
});

</script>

</html>