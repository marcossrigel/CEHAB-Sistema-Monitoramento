<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="formulario.css">
  <title>Formulário</title>
</head>

<body>
  <form class="formulario" action="https://api.sheetmonkey.io/form/rwVomc1Em28Zsyda6S28xx" method="post">
    <h1 class="main-title">Criar uma nova iniciativa</h1>

    <div class="linha">
      <div class="campo-pequeno">
        <label class="label">Nome da Iniciativa</label>
        <input type="text" name="iniciativa" class="campo">
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
        <label class="label">Nº Processo SEI</label>
        <input type="text" name="ib_numero_processo_sei">
      </div>
      <div class="campo">
        <label class="label">Fiscal</label>
        <input type="text" name="ib_fiscal">
      </div>
      <div class="campo-longo">
        <label class="label">Gestor Responsável</label>
        <input type="text" name="ib_gestor_responsavel">
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
    <a href="home.html">
      <p class="texto-login">Cancelar</p>
    </a>
  </form>
</body>
</html>
