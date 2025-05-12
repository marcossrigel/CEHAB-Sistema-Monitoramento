<?php

$dbHost = getenv('MYSQLHOST');          // mysql.railway.internal
$dbUsername = getenv('MYSQLUSER');      // root
$dbPassword = getenv('MYSQLPASSWORD');  // senha do Railway
$dbName = getenv('MYSQLDATABASE');      // railway

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

var_dump(getenv('MYSQLHOST'))

// Opcional: debug de conexão
/*
if ($conexao->connect_errno) {
    echo "Erro ao conectar: " . $conexao->connect_error;
} else {
    echo "Conexão efetuada com sucesso!";
}
*/
?>
