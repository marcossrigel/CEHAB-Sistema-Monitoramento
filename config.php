<?php

$dbHost = getenv('MYSQLHOST');
$dbUsername = getenv('MYSQLUSER');
$dbPassword = getenv('MYSQLPASSWORD');
$dbName = getenv('MYSQLDATABASE');

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

/*
  if($conexao->connect_errno)
  {
    echo "Erro";
  }
  else {
    echo "Conexão efetuada com sucesso";
  }
*/
?>